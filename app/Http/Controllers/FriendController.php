<?php

namespace App\Http\Controllers;

use App\Mail\FriendRequestReceived;
use App\Models\ChatConversation;
use App\Models\Friend;
use App\Models\FriendBlock;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FriendController extends Controller
{
    public function sendRequest(Request $request, int $user): RedirectResponse
    {
        $sender = $request->user();
        $receiver = User::findOrFail($user);

        if ((int) $sender->id === (int) $receiver->id) {
            return back()->with('status', 'You cannot send a friend request to yourself.');
        }

        if ($this->areFriends($sender->id, $receiver->id)) {
            return back()->with('status', 'You are already friends.');
        }

        if (FriendBlock::existsBetween((int) $sender->id, (int) $receiver->id)) {
            return back()->with('status', 'You cannot send a friend request to this user.');
        }

        $existingIncoming = FriendRequest::where('sender_id', $receiver->id)
            ->where('receiver_id', $sender->id)
            ->where('status', 0)
            ->first();

        if ($existingIncoming) {
            return back()->with('status', 'This user already sent you a friend request. Accept or reject it from your friends page.');
        }

        $friendRequest = FriendRequest::firstOrNew([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);

        if ((int) ($friendRequest->status ?? 0) === 0 && $friendRequest->exists) {
            return back()->with('status', 'Friend request already sent.');
        }

        $friendRequest->fill([
            'status' => 0,
            'created_at' => now(),
            'responded_at' => null,
        ])->save();

        Mail::to($receiver->email)->send(new FriendRequestReceived($sender, $receiver));

        return back()->with('status', 'Friend request sent.');
    }

    public function accept(int $requestId): RedirectResponse
    {
        $friendRequest = FriendRequest::where('id', $requestId)
            ->where('receiver_id', Auth::id())
            ->where('status', 0)
            ->firstOrFail();

        $friendRequest->forceFill([
            'status' => 1,
            'responded_at' => now(),
        ])->save();

        $this->createFriendship((int) $friendRequest->sender_id, (int) $friendRequest->receiver_id);

        return back()->with('status', 'Friend request accepted.');
    }

    public function reject(int $requestId): RedirectResponse
    {
        $friendRequest = FriendRequest::where('id', $requestId)
            ->where('receiver_id', Auth::id())
            ->where('status', 0)
            ->firstOrFail();

        $friendRequest->forceFill([
            'status' => 2,
            'responded_at' => now(),
        ])->save();

        return back()->with('status', 'Friend request rejected.');
    }

    public function unfriend(int $user): RedirectResponse
    {
        $currentUser = Auth::id();

        Friend::where(function ($query) use ($currentUser, $user) {
            $query->where('user_id', $currentUser)->where('friend_id', $user);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('user_id', $user)->where('friend_id', $currentUser);
        })->delete();

        return back()->with('status', 'Friend removed.');
    }

    public function block(Request $request, int $user): RedirectResponse
    {
        $blockerId = (int) $request->user()->id;

        if ($blockerId === $user) {
            return back()->with('status', 'You cannot block yourself.');
        }

        User::findOrFail($user);

        FriendBlock::firstOrCreate(
            ['blocker_id' => $blockerId, 'blocked_id' => $user],
            ['created_at' => now()]
        );

        // Blocking someone also ends any existing friendship and clears any
        // pending friend request between the two of you, in either direction.
        Friend::where(function ($query) use ($blockerId, $user) {
            $query->where('user_id', $blockerId)->where('friend_id', $user);
        })->orWhere(function ($query) use ($blockerId, $user) {
            $query->where('user_id', $user)->where('friend_id', $blockerId);
        })->delete();

        FriendRequest::where(function ($query) use ($blockerId, $user) {
            $query->where('sender_id', $blockerId)->where('receiver_id', $user);
        })->orWhere(function ($query) use ($blockerId, $user) {
            $query->where('sender_id', $user)->where('receiver_id', $blockerId);
        })->delete();

        // Blocking also pulls any existing conversation out of both inboxes
        // — same "hidden" mechanism a user already has for removing a
        // conversation from their own view, just applied to both sides at
        // once here.
        $this->setConversationHiddenBetween($blockerId, $user, true);

        return back()->with('status', 'User blocked.');
    }

    public function unblock(int $user): RedirectResponse
    {
        $currentUserId = (int) Auth::id();

        FriendBlock::where('blocker_id', $currentUserId)->where('blocked_id', $user)->delete();

        // Only bring the conversation back once neither side still has the
        // other blocked — the other person may independently still have
        // *me* blocked even after I've unblocked them.
        if (! FriendBlock::existsBetween($currentUserId, $user)) {
            $this->setConversationHiddenBetween($currentUserId, $user, false);
        }

        return back()->with('status', 'User unblocked.');
    }

    /**
     * Hide (or restore) any existing conversation between these two users
     * for both participants at once — used when a block starts or ends,
     * as opposed to the single-sided "remove this conversation from just
     * my inbox" a user can already do from the chat itself.
     */
    private function setConversationHiddenBetween(int $userId, int $otherUserId, bool $hidden): void
    {
        $conversation = ChatConversation::where(function ($query) use ($userId, $otherUserId) {
            $query->where('user_one_id', $userId)->where('user_two_id', $otherUserId);
        })->orWhere(function ($query) use ($userId, $otherUserId) {
            $query->where('user_one_id', $otherUserId)->where('user_two_id', $userId);
        })->first();

        if (! $conversation) {
            return;
        }

        $value = $hidden ? now() : null;

        $conversation->update([
            'user_one_hidden_at' => $value,
            'user_two_hidden_at' => $value,
        ]);
    }

    private function areFriends(int $userId, int $friendId): bool
    {
        return Friend::where('user_id', $userId)->where('friend_id', $friendId)->exists()
            || Friend::where('user_id', $friendId)->where('friend_id', $userId)->exists();
    }

    private function createFriendship(int $userId, int $friendId): void
    {
        Friend::updateOrCreate(
            ['user_id' => $userId, 'friend_id' => $friendId],
            ['created_at' => now()]
        );

        Friend::updateOrCreate(
            ['user_id' => $friendId, 'friend_id' => $userId],
            ['created_at' => now()]
        );
    }
}
