<?php

namespace App\Http\Controllers;

use App\Mail\FriendRequestReceived;
use App\Models\Friend;
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
