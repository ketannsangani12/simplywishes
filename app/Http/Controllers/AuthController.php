<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\City;
use App\Models\Country;
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
use App\Models\State;
use App\Models\User;
use App\Models\UserPresence;
use App\Models\Wish;
use App\Models\WishComment;
use App\Models\WishCommentLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('users.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->isDeleted()) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'The provided credentials do not match our records.'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->route('home')->with('status', 'Logged in successfully.');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Logged out successfully.');
    }

    public function showSignup(): View
    {
        $countries = Country::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        // Re-hydrate the dependent State/City dropdowns from the previously
        // submitted (and now flashed) country/state, so a failed signup
        // (e.g. "email already registered") redisplays the user's selection
        // instead of resetting the location fields to empty/disabled.
        $states = collect();
        $cities = collect();

        if (old('country')) {
            $states = State::query()
                ->where('country_id', old('country'))
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        if (old('state')) {
            $cities = City::query()
                ->where('state_id', old('state'))
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('users.signup', compact('countries', 'states', 'cities'));
    }

    public function signup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'country' => ['required', 'integer', 'exists:countries,id'],
            'state' => ['required', 'integer', 'exists:states,id'],
            'city' => ['required', 'integer', 'exists:cities,id'],
            'password' => ['required', 'min:6'],
            'confirm' => ['required', 'same:password'],
            'avatar' => ['nullable', 'image', 'max:10240'],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Please acknowledge you accept this condition.',
        ]);

        $country = Country::find($validated['country']);
        $state = State::find($validated['state']);
        $city = City::find($validated['city']);

        $profileImage = null;
        if ($request->hasFile('avatar')) {
            $extension = strtolower($request->file('avatar')->getClientOriginalExtension() ?: $request->file('avatar')->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                base_path('../public_html/uploads/users'),
                public_path('uploads/users'),
            ];

            $uploadDirectory = null;
            foreach ($candidateDirectories as $directory) {
                $parentDirectory = dirname($directory);
                if (is_dir($directory) || is_dir($parentDirectory)) {
                    $uploadDirectory = $directory;
                    break;
                }
            }

            $uploadDirectory ??= public_path('uploads/users');

            File::ensureDirectoryExists($uploadDirectory);
            $request->file('avatar')->move($uploadDirectory, $fileName);

            $profileImage = 'uploads/users/' . $fileName;
        } elseif ($request->filled('avatar-default')) {
            $profileImage = 'images/users-default/' . $request->input('avatar-default');
        }

        $user = User::create([
            'name' => trim($validated['first-name'] . ' ' . $validated['last-name']),
            'first_name' => $validated['first-name'],
            'last_name' => $validated['last-name'],
            'email' => $validated['email'],
            'about' => $request->input('about'),
            'country' => $country?->name,
            'state' => $state?->name,
            'city' => $city?->name,
            'profile_image' => $profileImage,
            'password' => Hash::make($validated['password']),
        ]);

        $user->sendEmailVerificationNotification();
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')->with('status', 'Verification link sent to your email.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'country' => ['required', 'integer', 'exists:countries,id'],
            'state' => ['required', 'integer', 'exists:states,id'],
            'city' => ['required', 'integer', 'exists:cities,id'],
            'avatar' => ['nullable', 'image', 'max:10240'],
            'avatar-default' => ['nullable', 'string'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $country = Country::find($validated['country']);
        $state = State::find($validated['state']);
        $city = City::find($validated['city']);

        $profileImage = $user->profile_image;

        if ($request->boolean('remove_avatar')) {
            $profileImage = null;
        }

        if ($request->hasFile('avatar')) {
            $extension = strtolower($request->file('avatar')->getClientOriginalExtension() ?: $request->file('avatar')->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                base_path('../public_html/uploads/users'),
                public_path('uploads/users'),
            ];

            $uploadDirectory = null;
            foreach ($candidateDirectories as $directory) {
                $parentDirectory = dirname($directory);
                if (is_dir($directory) || is_dir($parentDirectory)) {
                    $uploadDirectory = $directory;
                    break;
                }
            }

            $uploadDirectory ??= public_path('uploads/users');

            File::ensureDirectoryExists($uploadDirectory);
            $request->file('avatar')->move($uploadDirectory, $fileName);

            $profileImage = 'uploads/users/' . $fileName;
        } elseif ($request->filled('avatar-default')) {
            $profileImage = 'images/users-default/' . $request->input('avatar-default');
        }

        $user->fill([
            'name' => trim($validated['first-name'] . ' ' . $validated['last-name']),
            'first_name' => $validated['first-name'],
            'last_name' => $validated['last-name'],
            'email' => $validated['email'],
            'about' => $validated['about'] ?? null,
            'country' => $country?->name,
            'state' => $state?->name,
            'city' => $city?->name,
            'profile_image' => $profileImage,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
            'confirm' => ['required', 'same:password'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Named error bag so a failed delete-account attempt only shows its
        // error inside the delete-account modal — not in the page's general
        // error summary, which reads from the default (unnamed) bag.
        $request->validateWithBag('deleteAccount', [
            'delete_password' => ['required', 'string'],
        ], [
            'delete_password.required' => 'Please enter your password to confirm account deletion.',
        ]);

        if (! Hash::check($request->input('delete_password'), $user->password)) {
            return back()
                ->withErrors(['delete_password' => 'The password you entered is incorrect.'], 'deleteAccount')
                ->with('open_delete_account', true);
        }

        // Remove the physical file only for a user-uploaded avatar. Default
        // avatars live under images/users-default/ and are shared assets.
        if ($user->profile_image && str_starts_with($user->profile_image, 'uploads/users/')) {
            $path = public_path($user->profile_image);
            if (is_file($path)) {
                File::delete($path);
            }
        }

        // A genuine, complete erasure — not a rename. An earlier version of
        // this kept the row (renamed to "Deleted User") and left every post,
        // friendship, block, and chat conversation in place purely because
        // none of those tables have a real DB foreign key on the user id.
        // That's a privacy problem, not just a UX one: a deleted user's
        // wishes/donations stayed grantable/acceptable, their conversations
        // stayed open for the other side to keep messaging into, they still
        // turned up in friend search and could still receive friend
        // requests, and any wish/donation they'd granted/accepted for
        // someone else was stuck showing "Deleted User" forever. Everything
        // below removes all of it; see $this->eraseUserData().
        $this->eraseUserData((int) $user->id);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('status', 'Your account has been deleted. We\'re sorry to see you go.');
    }

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
    private function eraseUserData(int $userId): void
    {
        DB::transaction(function () use ($userId) {
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

            // Someone else's wish/donation this user granted/accepted as a
            // third party stays — it's not this user's post to erase — but
            // it can't go on citing a person who no longer exists. Put it
            // back the way it looked before this user ever got involved:
            // no grantor/acceptor, back to its normal "available" state,
            // free for someone else to grant/accept instead of stuck
            // showing "Deleted User" forever.
            Wish::withoutGlobalScopes()->where('granted_by', $userId)->update([
                'granted_by' => null,
                'granted_date' => null,
                'process_status' => 0,
                'process_granted_by' => null,
                'process_granted_date' => null,
                'wish_progress_status' => 0,
                'grant_note' => null,
            ]);

            Donation::withoutGlobalScopes()->where('accepted_by', $userId)->update([
                'accepted_by' => null,
                'accepted_at' => null,
                'status' => 1,
                'process_status' => 0,
                'process_granted_by' => null,
                'process_granted_date' => null,
            ]);

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
            UserPresence::where('user_id', $userId)->delete();
            Report::where('reporter_id', $userId)->orWhere('reported_user_id', $userId)->delete();

            // The account itself, last — everything above no longer
            // references it, so nothing is left dangling.
            User::withoutGlobalScopes()->whereKey($userId)->delete();
        });
    }

    public function statesByCountry(int $country): JsonResponse
    {
        $states = State::query()
            ->where('country_id', $country)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    public function citiesByState(int $state): JsonResponse
    {
        $cities = City::query()
            ->where('state_id', $state)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function showForgotPassword(): View
    {
        return view('users.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'reset-email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $request->input('reset-email'),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()
                ->route('password.forgot')
                ->with('status', __($status));
        }

        return back()
            ->withErrors(['reset-email' => __($status)])
            ->onlyInput('reset-email');
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('users.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', __($status));
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // Fallback for local dev if session state is lost.
            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User';
        $firstName = $googleUser->user['given_name'] ?? null;
        $lastName = $googleUser->user['family_name'] ?? null;

        // Excludes deleted accounts deliberately: their email was replaced
        // with a placeholder on deletion (see deleteAccount()), so a real
        // match here should never happen — this is defense in depth against
        // ever reactivating a deleted account instead of creating a fresh one.
        $user = User::where('email', $email)->whereNull('deleted_at')->first();
        if (!$user) {
            $user = new User();
            $user->email = $email;
            $user->password = Hash::make(Str::random(32));
        }

        $user->name = $name;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->google_id = $googleUser->getId();
        $user->profile_image = $googleUser->getAvatar();
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->role = $user->role ?: 'User';
        $user->status = $user->status ?: 'Active';
        $user->udid = $user->udid ?: Str::uuid()->toString();
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Logged in with Google.');
    }
}
