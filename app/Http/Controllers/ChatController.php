<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\FriendBlock;
use App\Models\User;
use App\Models\UserPresence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    private static ?bool $chatMessagesHaveDeletedAt = null;

    public function index(Request $request): View
    {
        $userId = Auth::id();

        // Arriving from a "Message" action (e.g. a member profile) ensures a conversation exists.
        $requestedUserId = (int) $request->query('user', 0);
        $forcedConversationId = 0;
        if ($requestedUserId && $requestedUserId !== (int) $userId && User::whereKey($requestedUserId)->exists()) {
            $forcedConversationId = (int) $this->findOrCreateConversation((int) $userId, $requestedUserId)->id;
        }

        $conversations = $this->conversationQuery($userId)
            ->with([
                'userOne.presence',
                'userTwo.presence',
                'latestMessage.sender',
            ])
            ->get()
            ->map(fn (ChatConversation $conversation) => $this->conversationPayload($conversation, $userId));

        $selectedConversationId = $forcedConversationId ?: (int) $request->query('conversation', $conversations->first()['id'] ?? 0);
        $selectedConversation = $selectedConversationId
            ? ChatConversation::forUser($userId)->where('id', $selectedConversationId)->first()
            : null;

        if (! $selectedConversation && $conversations->isNotEmpty()) {
            $selectedConversation = ChatConversation::forUser($userId)->where('id', $conversations->first()['id'])->first();
            $selectedConversationId = (int) ($selectedConversation?->id ?? 0);
        }

        $selectedMessages = collect();
        $participant = [];

        if ($selectedConversation) {
            $messages = $this->messageQuery($selectedConversation->id, $userId)
                ->with(['sender', 'replyTo.sender'])
                ->orderBy('id')
                ->limit(50)
                ->get();

            $reportedMessageIds = $this->reportedMessageIds($userId, $messages->pluck('id'));

            $selectedMessages = $messages->map(
                fn (ChatMessage $message) => $this->messagePayload($message, $userId, $reportedMessageIds)
            );

            $participant = $this->participantPayload(
                User::with('presence')->find($selectedConversation->otherParticipantId($userId))
            );
        }

        return view('users.user.inbox', compact(
            'conversations',
            'selectedConversationId',
            'selectedMessages',
            'participant'
        ));
    }

    public function threads(): JsonResponse
    {
        $userId = Auth::id();

        $conversations = $this->conversationQuery($userId)
            ->with([
                'userOne.presence',
                'userTwo.presence',
                'latestMessage.sender',
            ])
            ->get()
            ->map(fn (ChatConversation $conversation) => $this->conversationPayload($conversation, $userId));

        return response()->json(['conversations' => $conversations]);
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $searchTerm = trim((string) $request->query('q', ''));

        $users = User::with('presence')
            ->where('id', '!=', $userId)
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%")
                        ->orWhereRaw("CONCAT_WS(' ', first_name, last_name) like ?", ["%{$searchTerm}%"]);
                });
            })
            ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), last_name) ASC')
            ->limit(8)
            ->get()
            ->map(function (User $user) {
                $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));
                $name = $name !== '' ? $name : ($user->name ?: 'Member');

                return [
                    'id' => $user->id,
                    'name' => $name,
                    'avatar' => $this->avatarForUser($user, $name),
                    'is_online' => $user->presence?->isOnline() ?? false,
                ];
            });

        return response()->json(['users' => $users]);
    }

    public function openConversation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $authId = Auth::id();
        $otherId = (int) $validated['user_id'];

        if ($otherId === $authId) {
            return response()->json(['message' => 'You cannot message yourself.'], 422);
        }

        if (FriendBlock::existsBetween($authId, $otherId)) {
            return response()->json(['message' => 'You cannot message this user.'], 403);
        }

        $conversation = $this->findOrCreateConversation($authId, $otherId);
        $conversation->load(['userOne.presence', 'userTwo.presence', 'latestMessage.sender']);

        return response()->json([
            'conversation' => $this->conversationPayload($conversation, $authId),
            'participant' => $this->participantPayload(User::with('presence')->find($otherId)),
        ]);
    }

    public function messages(Request $request, int $conversation): JsonResponse
    {
        $userId = Auth::id();
        $conversation = ChatConversation::forUser($userId)->findOrFail($conversation);
        $after = (int) $request->query('after', 0);

        $messages = $this->messageQuery($conversation->id, $userId)
            ->with(['sender', 'replyTo.sender'])
            ->when($after > 0, fn ($query) => $query->where('id', '>', $after))
            ->orderBy('id')
            ->get();

        $reportedMessageIds = $this->reportedMessageIds($userId, $messages->pluck('id'));

        $messages = $messages->map(
            fn (ChatMessage $message) => $this->messagePayload($message, $userId, $reportedMessageIds)
        );

        $lastReadId = (int) ChatMessage::where('conversation_id', $conversation->id)
            ->where('sender_id', $userId)
            ->when($this->chatMessagesHaveDeletedAt(), fn ($query) => $query->whereNull('deleted_at'))
            ->whereNotNull('read_at')
            ->max('id');

        $deletedIds = ChatMessage::where('conversation_id', $conversation->id)
            ->when($this->chatMessagesHaveDeletedAt(), fn ($query) => $query->whereNotNull('deleted_at'))
            ->pluck('id')
            ->map(fn ($id) => (int) $id);

        $participant = User::with('presence')->find($conversation->otherParticipantId($userId));

        return response()->json([
            'messages' => $messages,
            'last_read_id' => $lastReadId,
            'deleted_ids' => $deletedIds,
            'participant' => $this->participantPayload($participant),
            'conversation' => $this->conversationPayload(
                $conversation->load(['userOne.presence', 'userTwo.presence', 'latestMessage.sender']),
                $userId
            ),
        ]);
    }

    public function sendMessage(Request $request, int $conversation): JsonResponse
    {
        $userId = Auth::id();
        $conversation = ChatConversation::forUser($userId)->findOrFail($conversation);

        if (FriendBlock::existsBetween((int) $userId, $conversation->otherParticipantId((int) $userId))) {
            return response()->json(['message' => 'You cannot message this user.'], 403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'reply_to_id' => [
                'nullable',
                'integer',
                Rule::exists('chat_messages', 'id')->where('conversation_id', $conversation->id),
            ],
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
            'body' => trim($validated['body']),
        ]);

        // A fresh message means the conversation is live again for both
        // sides, even if either of them had previously removed it.
        $conversation->update([
            'last_message_at' => now(),
            'user_one_hidden_at' => null,
            'user_two_hidden_at' => null,
        ]);

        return response()->json([
            'message' => $this->messagePayload($message->load(['sender', 'replyTo.sender']), $userId),
        ]);
    }

    public function deleteMessage(int $conversation, int $message): JsonResponse
    {
        $userId = Auth::id();
        $conversation = ChatConversation::forUser($userId)->findOrFail($conversation);
        $message = ChatMessage::where('conversation_id', $conversation->id)->findOrFail($message);

        if ((int) $message->sender_id !== (int) $userId) {
            return response()->json(['message' => 'You can only delete your own messages.'], 403);
        }

        if (! $this->chatMessagesHaveDeletedAt()) {
            $message->delete();
        } elseif ($message->deleted_at === null) {
            $message->update(['deleted_at' => now()]);
        }

        return response()->json(['ok' => true, 'id' => (int) $message->id]);
    }

    public function reportMessage(int $conversation, int $message): JsonResponse
    {
        $userId = Auth::id();
        $conversation = ChatConversation::forUser($userId)->findOrFail($conversation);
        $message = ChatMessage::where('conversation_id', $conversation->id)->findOrFail($message);

        if ((int) $message->sender_id === (int) $userId) {
            return response()->json(['message' => 'You cannot report your own message.'], 403);
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => $userId,
                'reportable_type' => 'chat_message',
                'reportable_id' => $message->id,
            ],
            [
                'reported_user_id' => $message->sender_id,
                'reason' => 'Chat message',
                'details' => 'Chat message reported from inbox.',
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return response()->json(['ok' => true, 'id' => (int) $message->id]);
    }

    public function destroyConversation(int $conversation): JsonResponse
    {
        $userId = Auth::id();
        $conversation = ChatConversation::forUser($userId)->findOrFail($conversation);

        // This only removes the conversation from the current user's own
        // inbox; the other participant keeps their copy and full history
        // until they choose to remove it too. It reappears for this user
        // if either side sends a new message into it.
        $conversation->update([
            $conversation->hiddenColumnFor((int) $userId) => now(),
        ]);

        return response()->json(['ok' => true, 'id' => (int) $conversation->id]);
    }

    public function heartbeat(): JsonResponse
    {
        UserPresence::updateOrCreate(
            ['user_id' => Auth::id()],
            ['last_seen_at' => now()]
        );

        return response()->json(['ok' => true]);
    }

    private function conversationQuery(int $userId)
    {
        return ChatConversation::forUser($userId)
            ->where(function ($query) use ($userId) {
                $query->where(fn ($q) => $q->where('user_one_id', $userId)->whereNull('user_one_hidden_at'))
                    ->orWhere(fn ($q) => $q->where('user_two_id', $userId)->whereNull('user_two_hidden_at'));
            })
            // Starting a new chat creates the conversation row right away so a
            // message can be sent into it, but until an actual message exists
            // it's just a draft — it shouldn't clutter the inbox list.
            ->whereHas('messages')
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at');
    }

    private function messageQuery(int $conversationId, int $userId)
    {
        DB::table('chat_messages')
            ->where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return ChatMessage::where('conversation_id', $conversationId);
    }

    private function findOrCreateConversation(int $initiatingUserId, int $otherUserId): ChatConversation
    {
        $firstId = min($initiatingUserId, $otherUserId);
        $secondId = max($initiatingUserId, $otherUserId);

        $conversation = ChatConversation::firstOrCreate(
            [
                'user_one_id' => $firstId,
                'user_two_id' => $secondId,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        // Re-opening a conversation (e.g. from the "New Message" modal, or a
        // member profile's "Message" button) should bring it back into the
        // initiating user's inbox even if they had previously removed it.
        if ($conversation->isHiddenFor($initiatingUserId)) {
            $conversation->update([$conversation->hiddenColumnFor($initiatingUserId) => null]);
        }

        return $conversation;
    }

    private function conversationPayload(ChatConversation $conversation, int $userId): array
    {
        $other = (int) $conversation->user_one_id === (int) $userId ? $conversation->userTwo : $conversation->userOne;
        $name = trim(implode(' ', array_filter([$other?->first_name ?? null, $other?->last_name ?? null])));
        $name = $name !== '' ? $name : ($other?->name ?? 'Member');
        $latestMessage = $conversation->latestMessage;

        $lastMessagePreview = $latestMessage
            ? ($latestMessage->isDeleted() ? 'This message was deleted' : $latestMessage->body)
            : '';

        return [
            'id' => $conversation->id,
            'name' => $name,
            'avatar' => $this->avatarForUser($other, $name),
            'is_online' => $other?->presence?->isOnline() ?? false,
            'status_label' => $other?->presence?->isOnline() ? 'Online' : 'Offline',
            'last_message' => $lastMessagePreview,
            'last_message_at' => optional($latestMessage?->created_at ?? $conversation->last_message_at)->format('M d'),
            'unread_count' => ChatMessage::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $userId)
                ->when($this->chatMessagesHaveDeletedAt(), fn ($query) => $query->whereNull('deleted_at'))
                ->whereNull('read_at')
                ->count(),
        ];
    }

    private function chatMessagesHaveDeletedAt(): bool
    {
        if (self::$chatMessagesHaveDeletedAt === null) {
            self::$chatMessagesHaveDeletedAt = Schema::hasColumn('chat_messages', 'deleted_at');
        }

        return self::$chatMessagesHaveDeletedAt;
    }

    private function reportedMessageIds(int $userId, $messageIds): array
    {
        $messageIds = collect($messageIds)->filter()->values();

        if ($messageIds->isEmpty()) {
            return [];
        }

        return DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'chat_message')
            ->whereIn('reportable_id', $messageIds)
            ->where('status', 0)
            ->pluck('reportable_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function participantPayload(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));
        $name = $name !== '' ? $name : ($user->name ?: 'Member');
        $online = $user->presence?->isOnline() ?? false;

        return [
            'id' => $user->id,
            'name' => $name,
            'avatar' => $this->avatarForUser($user, $name),
            'is_online' => $online,
            'status_label' => $online ? 'Online' : 'Offline',
            'about' => $user->about,
            'location' => trim(implode(', ', array_filter([$user->city ?? null, $user->state ?? null, $user->country ?? null]))),
        ];
    }

    private function messagePayload(ChatMessage $message, int $userId, array $reportedMessageIds = []): array
    {
        $sender = $message->sender;
        $name = trim(implode(' ', array_filter([$sender?->first_name ?? null, $sender?->last_name ?? null])));
        $name = $name !== '' ? $name : ($sender?->name ?? 'Member');
        $deleted = $message->isDeleted();

        return [
            'id' => $message->id,
            'body' => $deleted ? '' : $message->body,
            'is_deleted' => $deleted,
            'is_mine' => (int) $message->sender_id === (int) $userId,
            'is_reported_by_me' => in_array((int) $message->id, $reportedMessageIds, true),
            'sender_name' => $name,
            'sender_avatar' => $this->avatarForUser($sender, $name),
            'created_at' => $message->created_at?->format('M d, g:i A'),
            'read_at' => $message->read_at?->format('M d, g:i A'),
            'reply_to' => $this->replyPreview($message->replyTo),
        ];
    }

    private function replyPreview(?ChatMessage $message): ?array
    {
        if (! $message) {
            return null;
        }

        $deleted = $message->isDeleted();
        $sender = $message->sender;
        $name = trim(implode(' ', array_filter([$sender?->first_name ?? null, $sender?->last_name ?? null])));
        $name = $name !== '' ? $name : ($sender?->name ?? 'Member');

        return [
            'id' => (int) $message->id,
            'sender_name' => $name,
            'body' => $deleted ? '' : Str::limit((string) $message->body, 120),
            'is_deleted' => $deleted,
        ];
    }

    private function avatarForUser(?User $user, string $name): string
    {
        if (! $user?->profile_image) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=E2E8F0&color=0F172A';
        }

        return filter_var($user->profile_image, FILTER_VALIDATE_URL) ? $user->profile_image : asset($user->profile_image);
    }
}
