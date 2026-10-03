<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Donation;
use App\Models\DonationComment;
use App\Models\DonationCommentLike;
use App\Models\ForumComment;
use App\Models\ForumCommentLike;
use App\Models\ForumLike;
use App\Models\ForumPost;
use App\Models\Friend;
use App\Models\FriendBlock;
use App\Models\FriendRequest;
use App\Models\HappyStory;
use App\Models\HappyStoryComment;
use App\Models\HappyStoryCommentLike;
use App\Models\Report;
use App\Models\User;
use App\Models\UserPresence;
use App\Models\Wish;
use App\Models\WishComment;
use App\Models\WishCommentLike;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Permanently erases a user account and everything tied to it. Used by
 * "Delete my account" (AuthController::deleteAccount) and by the
 * users:erase-deleted command, which cleans up accounts deleted under the
 * old "rename to Deleted User" behaviour.
 */
class AccountEraser
{
    /**
     * Wipe every trace of a user from the site, then remove the account
     * itself. Order doesn't matter for referential integrity — none of
     * these tables have a real foreign key on the user id (see the old
     * deleted_at migration's own note on that) — but it's grouped by
     * "their own content", "their footprint on other people's content",
     * "relationships", "chat", and finally the account row itself.
     *
     * withoutGlobalScopes() on every Wish/Donation/HappyStory/ForumPost
     * query here specifically bypasses ExcludeBlockedUsersScope: that scope
     * hides a blocked user's posts from the *current* session's normal
     * browsing, which is exactly wrong for an erasure sweep that must see
     * every row regardless of who this user has blocked or been blocked by.
     */
    public function erase(int $userId): void
    {
        $user = User::withoutGlobalScopes()->find($userId);
        $email = $user?->email;

        // Every file this user uploaded: avatar, wish/donation/story images,
        // forum thumbnails and uploaded videos. Collected up front (the rows
        // that name them are deleted below) and removed once the data is gone.
        $uploadedFiles = collect([$user?->profile_image])
            ->merge(Wish::withoutGlobalScopes()->where('wished_by', $userId)->pluck('primary_image'))
            ->merge(Donation::withoutGlobalScopes()->where('created_by', $userId)->pluck('image'))
            ->merge(HappyStory::withoutGlobalScopes()->where('user_id', $userId)->pluck('story_image'))
            ->merge(ForumPost::withoutGlobalScopes()->where('created_by', $userId)
                ->get(['e_image', 'article_image', 'featured_video_url'])
                ->flatMap(fn ($post) => [$post->e_image, $post->article_image, $post->featured_video_url]))
            ->filter()
            ->unique()
            ->values();

        DB::transaction(function () use ($userId, $email) {
            $ownWishIds = Wish::withoutGlobalScopes()->where('wished_by', $userId)->pluck('w_id');
            $ownDonationIds = Donation::withoutGlobalScopes()->where('created_by', $userId)->pluck('id');
            $ownForumIds = ForumPost::withoutGlobalScopes()->where('created_by', $userId)->pluck('e_id');
            $ownStoryIds = HappyStory::withoutGlobalScopes()->where('user_id', $userId)->pluck('hs_id');

            // Comments (anyone's) and comment-likes on this user's own posts
            // — the posts themselves are about to be deleted, so nothing
            // should be left pointing at them.
            $wishCommentIds = WishComment::whereIn('wish_id', $ownWishIds)->pluck('id');
            WishCommentLike::whereIn('comment_id', $wishCommentIds)->delete();
            WishComment::whereIn('wish_id', $ownWishIds)->delete();

            $donationCommentIds = DonationComment::whereIn('donation_id', $ownDonationIds)->pluck('id');
            DonationCommentLike::whereIn('comment_id', $donationCommentIds)->delete();
            DonationComment::whereIn('donation_id', $ownDonationIds)->delete();

            $forumCommentIds = ForumComment::whereIn('forum_id', $ownForumIds)->pluck('id');
            ForumCommentLike::whereIn('comment_id', $forumCommentIds)->delete();
            ForumComment::whereIn('forum_id', $ownForumIds)->delete();
            ForumLike::whereIn('forum_id', $ownForumIds)->delete();

            $storyCommentIds = HappyStoryComment::whereIn('happy_story_id', $ownStoryIds)->pluck('id');
            HappyStoryCommentLike::whereIn('comment_id', $storyCommentIds)->delete();
            HappyStoryComment::whereIn('happy_story_id', $ownStoryIds)->delete();

            // This user's own comments/likes on *other* people's posts.
            $commentedElsewhereWishIds = WishComment::where('user_id', $userId)->pluck('id');
            WishCommentLike::whereIn('comment_id', $commentedElsewhereWishIds)->delete();
            WishCommentLike::where('user_id', $userId)->delete();
            WishComment::where('user_id', $userId)->delete();

            $commentedElsewhereDonationIds = DonationComment::where('user_id', $userId)->pluck('id');
            DonationCommentLike::whereIn('comment_id', $commentedElsewhereDonationIds)->delete();
            DonationCommentLike::where('user_id', $userId)->delete();
            DonationComment::where('user_id', $userId)->delete();

            $commentedElsewhereForumIds = ForumComment::where('user_id', $userId)->pluck('id');
            ForumCommentLike::whereIn('comment_id', $commentedElsewhereForumIds)->delete();
            ForumCommentLike::where('user_id', $userId)->delete();
            ForumComment::where('user_id', $userId)->delete();
            ForumLike::where('user_id', $userId)->delete();

            $commentedElsewhereStoryIds = HappyStoryComment::where('user_id', $userId)->pluck('id');
            HappyStoryCommentLike::whereIn('comment_id', $commentedElsewhereStoryIds)->delete();
            HappyStoryCommentLike::where('user_id', $userId)->delete();
            HappyStoryComment::where('user_id', $userId)->delete();

            // The posts themselves — every phase: draft, active/current,
            // in progress, granted/completed, saved by someone else.
            Wish::withoutGlobalScopes()->where('wished_by', $userId)->delete();
            Donation::withoutGlobalScopes()->where('created_by', $userId)->delete();
            ForumPost::withoutGlobalScopes()->where('created_by', $userId)->delete();
            HappyStory::withoutGlobalScopes()->where('user_id', $userId)->delete();

            // Other people's saves/likes of this user's posts (the posts are
            // gone, so these would only be dangling references).
            Activity::whereIn('wish_id', $ownWishIds)->orWhereIn('donation_id', $ownDonationIds)->delete();
            DB::table('happy_story_likes')->whereIn('happy_story_id', $ownStoryIds)->delete();

            // Someone else's wish/donation this user granted/accepted as a
            // third party stays — it's not this user's post to erase — but
            // it can't go on citing a person who no longer exists.
            //
            // Still in progress: put it back the way it looked before this
            // user got involved — no grantor/acceptor, back to "available",
            // free for someone else to grant/accept — instead of leaving the
            // owner waiting to confirm a fulfilment that can never happen.
            Wish::withoutGlobalScopes()->where('granted_by', $userId)->where('wish_progress_status', 1)->update([
                'granted_by' => null,
                'granted_date' => null,
                'process_status' => 0,
                'process_granted_by' => null,
                'process_granted_date' => null,
                'wish_progress_status' => 0,
                'grant_note' => null,
            ]);

            Donation::withoutGlobalScopes()->where('accepted_by', $userId)->where('status', 2)->update([
                'accepted_by' => null,
                'accepted_at' => null,
                'status' => 1,
                'process_status' => 0,
                'process_granted_by' => null,
                'process_granted_date' => null,
            ]);

            // Already granted/completed: the owner did receive it, so it
            // stays granted — only the link to this user is removed (the
            // page then shows "Granted by Another user", no name).
            Wish::withoutGlobalScopes()->where('granted_by', $userId)->update([
                'granted_by' => null,
                'process_granted_by' => null,
                'grant_note' => null,
            ]);
            Wish::withoutGlobalScopes()->where('process_granted_by', $userId)->update(['process_granted_by' => null]);
            Wish::withoutGlobalScopes()->where('fulfilled_by', $userId)->update(['fulfilled_by' => null]);

            Donation::withoutGlobalScopes()->where('accepted_by', $userId)->update([
                'accepted_by' => null,
                'process_granted_by' => null,
            ]);
            Donation::withoutGlobalScopes()->where('process_granted_by', $userId)->update(['process_granted_by' => null]);
            Donation::withoutGlobalScopes()->where('completed_by', $userId)->update(['completed_by' => null]);

            ForumPost::withoutGlobalScopes()->where('updated_by', $userId)->update(['updated_by' => null]);

            // Friends, pending requests (sent or received), and blocks (in
            // either direction).
            Friend::where('user_id', $userId)->orWhere('friend_id', $userId)->delete();
            FriendRequest::where('sender_id', $userId)->orWhere('receiver_id', $userId)->delete();
            FriendBlock::where('blocker_id', $userId)->orWhere('blocked_id', $userId)->delete();

            // Every chat conversation involving this user, messages included
            // — not just hidden from view the way blocking hides one, gone.
            $conversationIds = ChatConversation::where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId)
                ->pluck('id');
            ChatMessage::whereIn('conversation_id', $conversationIds)->delete();
            ChatConversation::whereIn('id', $conversationIds)->delete();

            // This user's own likes/saves on other people's posts, their
            // online-presence record, and any moderation reports naming them
            // (filed by them, or filed about them).
            Activity::where('user_id', $userId)->delete();
            DB::table('happy_story_likes')->where('user_id', $userId)->delete();
            UserPresence::where('user_id', $userId)->delete();
            Report::where('reporter_id', $userId)->orWhere('reported_user_id', $userId)->delete();

            // Signed-in sessions on other devices and any pending password
            // reset link, so nothing can still act as (or recover) the account.
            if (Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $userId)->delete();
            }
            if ($email && Schema::hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->where('email', $email)->delete();
            }

            // The account itself, last — everything above no longer
            // references it, so nothing is left dangling.
            User::withoutGlobalScopes()->whereKey($userId)->delete();
        });

        // Uploaded files (avatar, wish/donation/story images, forum
        // thumbnails and videos) only after the data is gone for good, so a
        // failed transaction never leaves posts pointing at deleted files.
        foreach ($uploadedFiles as $path) {
            $this->deleteUploadedFile($path);
        }
    }

    /**
     * Delete a user-uploaded file. Only paths under uploads/ are touched —
     * default images (images/users-default/, images/wishes-default/, …) are
     * shared by everyone. Checks both public/ and the live server's
     * ../public_html/, same as the controllers that serve these files.
     */
    private function deleteUploadedFile(?string $path): void
    {
        $path = ltrim((string) $path, '/');
        if ($path === '' || ! str_starts_with($path, 'uploads/') || str_contains($path, '..')) {
            return;
        }

        foreach ([public_path($path), base_path('../public_html/' . $path)] as $file) {
            if (is_file($file)) {
                File::delete($file);
            }
        }
    }
}
