@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'My Friends - Simply Wishes')

@section('content')
@php
  $friendTabs = $tabs;

  $displayName = function ($user) {
    if (! $user) {
      return 'Unknown user';
    }

    $name = trim(implode(' ', array_filter([
      $user->first_name ?? null,
      $user->last_name ?? null,
    ])));

    if ($name !== '') {
      return $name;
    }

    if (! empty($user->name)) {
      return $user->name;
    }

    if (! empty($user->username)) {
      return $user->username;
    }

    return $user->email ?: 'Unknown user';
  };

  $avatarFor = function ($user, $label) {
    if ($user && ! empty($user->profile_image)) {
      return asset($user->profile_image);
    }

    return 'https://ui-avatars.com/api/?name=' . urlencode($label) . '&background=E2E8F0&color=0F172A';
  };

  $buildWishItem = function ($wish, string $statusLabel, string $statusClass) use ($displayName, $avatarFor, $relatedUsers) {
    $owner = $relatedUsers->get($wish->wished_by);
    $ownerName = $displayName($owner);

    return [
      'type' => 'wish',
      'id' => $wish->w_id,
      'title' => $wish->wish_title ?: 'Untitled wish',
      'description' => $wish->wish_description ?: 'No description yet.',
      'image' => $wish->imageUrl() ?: 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
      'link' => route('wishes.show', $wish->w_id),
      'status' => $statusLabel,
      'statusClass' => $statusClass,
      'ownerName' => $ownerName,
      'ownerAvatar' => $avatarFor($owner, $ownerName),
      'ownerLabel' => 'Friend wish',
      'sort_id' => (int) $wish->w_id,
    ];
  };

  $buildDonationItem = function ($donation, string $statusLabel, string $statusClass) use ($displayName, $avatarFor, $relatedUsers) {
    $owner = $relatedUsers->get($donation->created_by);
    $ownerName = $displayName($owner);

    return [
      'type' => 'donation',
      'id' => $donation->id,
      'title' => $donation->title ?: 'Untitled donation',
      'description' => $donation->description ?: 'No description yet.',
      'image' => $donation->imageUrl() ?: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
      'link' => route('donations.show', $donation->id),
      'status' => $statusLabel,
      'statusClass' => $statusClass,
      'ownerName' => $ownerName,
      'ownerAvatar' => $avatarFor($owner, $ownerName),
      'ownerLabel' => 'Friend donation',
      'sort_id' => (int) $donation->id,
    ];
  };

  $friendFeedItems = [
    'active' => collect($friendItemsByTab['active']['wishes'])
      ->map(fn ($wish) => $buildWishItem($wish, 'Active', 'bg-brand-blue-light/90 text-white'))
      ->concat(collect($friendItemsByTab['active']['donations'])
        ->map(fn ($donation) => $buildDonationItem($donation, 'Active', 'bg-brand-blue-light/90 text-white')))
      ->sortByDesc('sort_id')
      ->values(),
    'granted' => collect($friendItemsByTab['granted']['wishes'])
      ->map(fn ($wish) => $buildWishItem($wish, 'Granted', 'bg-emerald-600/90 text-white'))
      ->concat(collect($friendItemsByTab['granted']['donations'])
        ->map(fn ($donation) => $buildDonationItem($donation, 'Granted', 'bg-emerald-600/90 text-white')))
      ->sortByDesc('sort_id')
      ->values(),
    'progress' => collect($friendItemsByTab['progress']['wishes'])
      ->map(fn ($wish) => $buildWishItem($wish, 'In Progress', 'bg-amber-500/90 text-white'))
      ->concat(collect($friendItemsByTab['progress']['donations'])
        ->map(fn ($donation) => $buildDonationItem($donation, 'In Progress', 'bg-amber-500/90 text-white')))
      ->sortByDesc('sort_id')
      ->values(),
  ];

  $currentFeedItems = $tab === 'friends'
    ? collect()
    : ($friendFeedItems[$tab] ?? collect());

@endphp

<main class="flex-1 bg-gradient-to-b from-white via-slate-50 to-slate-100 dark:from-background-dark dark:via-[#0f172a] dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <div class="flex flex-col gap-6">
      <div class="flex flex-col gap-3">
        <p class="text-sm uppercase tracking-[0.24em] text-text-muted-light dark:text-text-muted-dark">Community</p>
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
          <div class="max-w-2xl">
            <h1 class="text-3xl sm:text-4xl font-bold text-brand-blue-light dark:text-white">My Friends</h1>
            <p class="mt-2 text-text-muted-light dark:text-text-muted-dark">
              Search users, send friend requests, and manage incoming requests from one place.
            </p>
          </div>
          <form method="GET" action="{{ route('my.friends') }}" class="w-full lg:max-w-xl">
            <div class="flex gap-3">
              <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted-light">search</span>
                <input
                  type="search"
                  name="q"
                  value="{{ $searchTerm }}"
                  placeholder="Search by name, username, or email"
                  class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark pl-10 pr-4 py-3 text-text-light dark:text-text-dark focus:border-primary focus:ring-2 focus:ring-primary/30"
                />
              </div>
              <button
                type="submit"
                class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-brand-blue-light text-white font-semibold shadow hover:shadow-lg transition"
              >
                <span class="material-symbols-outlined text-base">search</span>
                Search
              </button>
            </div>
          </form>
        </div>
      </div>

      @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
          {{ session('status') }}
        </div>
      @endif

      <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-xl">
          <div class="flex flex-col gap-4 border-b border-gray-200 dark:border-gray-800 px-5 py-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <h2 class="text-lg font-semibold text-brand-blue-light dark:text-white">Friends Feed</h2>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Browse wishes and donations shared by your friends.</p>
              </div>
              @if ($searchTerm !== '')
                <span class="rounded-full bg-brand-blue-light/10 px-3 py-1 text-xs font-semibold text-brand-blue-light dark:text-brand-blue-dark">
                  Search: "{{ $searchTerm }}"
                </span>
              @endif
            </div>

            <div class="flex gap-2 overflow-x-auto pb-1">
              @foreach ($friendTabs as $tabKey => $tabLabel)
                <a
                  href="{{ route('my.friends', ['tab' => $tabKey]) }}"
                  class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition {{ $tab === $tabKey ? 'bg-brand-blue-light text-white shadow' : 'bg-slate-100 text-text-light hover:bg-slate-200 dark:bg-slate-900 dark:text-text-dark dark:hover:bg-slate-800' }}"
                >
                  {{ $tabLabel }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="p-5">
            @if ($tab === 'friends')
              @if ($friends->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-6 py-10 text-center">
                  <p class="text-lg font-semibold text-brand-blue-light dark:text-white">You have no friends yet</p>
                  <p class="mt-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                    Search for people above to send your first request.
                  </p>
                </div>
              @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                  @foreach ($friends as $friend)
                    @php
                      $friendName = $displayName($friend);
                      $friendAvatar = $avatarFor($friend, $friendName);
                    @endphp

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] p-4 shadow-sm">
                      <a href="{{ route('members.show', $friend->id) }}" class="flex items-start gap-4 group">
                        <img src="{{ $friendAvatar }}" alt="{{ $friendName }} avatar" class="h-14 w-14 rounded-xl object-cover" />
                        <div class="min-w-0 flex-1">
                          <h3 class="truncate text-base font-semibold text-brand-blue-light dark:text-white group-hover:underline">{{ $friendName }}</h3>
                          <p class="truncate text-sm text-text-muted-light dark:text-text-muted-dark">{{ $friend->email ?? '' }}</p>
                          @if (! empty($friend->username))
                            <p class="truncate text-xs text-text-muted-light/80 dark:text-text-muted-dark/80">@{{ $friend->username }}</p>
                          @endif
                        </div>
                      </a>

                      <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                          Friends
                        </span>
                        <form method="POST" action="{{ route('friends.unfriend', $friend->id) }}">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold text-text-light dark:text-text-dark hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-sm">person_remove</span>
                            Unfriend
                          </button>
                        </form>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            @elseif ($currentFeedItems->isEmpty())
              <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-6 py-10 text-center">
                <p class="text-lg font-semibold text-brand-blue-light dark:text-white">No items in this section</p>
                <p class="mt-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                  Your friends have nothing here yet.
                </p>
              </div>
            @else
              <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($currentFeedItems as $item)
                  <article class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] shadow-sm">
                    <div class="relative">
                      <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-52 w-full object-cover" />
                      <div class="absolute left-3 top-3">
                        <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow">
                          {{ $item['type'] === 'wish' ? 'Wish' : 'Donation' }}
                        </span>
                      </div>
                      <div class="absolute right-3 top-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow {{ $item['statusClass'] }}">
                          {{ $item['status'] }}
                        </span>
                      </div>
                    </div>
                    <div class="p-4 space-y-3">
                      <div class="flex items-center gap-3">
                        <img src="{{ $item['ownerAvatar'] }}" alt="{{ $item['ownerName'] }} avatar" class="h-10 w-10 rounded-full object-cover" />
                        <div class="min-w-0">
                          <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white">{{ $item['ownerName'] }}</p>
                          <p class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $item['ownerLabel'] }}</p>
                        </div>
                      </div>
                      <div>
                        <h3 class="font-semibold text-text-light dark:text-text-dark">{{ $item['title'] }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-subtle-light dark:text-subtle-dark">{{ $item['description'] }}</p>
                      </div>
                      <a class="inline-flex items-center text-emerald-600 font-semibold hover:underline" href="{{ $item['link'] }}">
                        Read More
                      </a>
                    </div>
                  </article>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-xl">
          <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 px-5 py-4">
            <div>
              <h2 class="text-lg font-semibold text-brand-blue-light dark:text-white">Search Users</h2>
              <p class="text-sm text-text-muted-light dark:text-text-muted-dark">This search only looks up users, not wishes or donations.</p>
            </div>
            @if ($searchTerm !== '')
              <span class="rounded-full bg-brand-blue-light/10 px-3 py-1 text-xs font-semibold text-brand-blue-light dark:text-brand-blue-dark">
                "{{ $searchTerm }}"
              </span>
            @endif
          </div>

          <div class="p-5">
            @if ($searchTerm === '')
              <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-6 py-10 text-center">
                <p class="text-lg font-semibold text-brand-blue-light dark:text-white">Search for users</p>
                <p class="mt-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                  Use the search box above to find users and send friend requests.
                </p>
              </div>
            @elseif ($searchResults->isEmpty())
              <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-6 py-10 text-center">
                <p class="text-lg font-semibold text-brand-blue-light dark:text-white">No users found</p>
                <p class="mt-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                  Try another search term.
                </p>
              </div>
            @else
              <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($searchResults as $user)
                  @php
                    $name = $displayName($user);
                    $avatar = $avatarFor($user, $name);
                    $isFriend = in_array((int) $user->id, array_map('intval', $friendIds), true);
                    $incomingRequest = $incomingRequests->firstWhere('sender_id', $user->id);
                    $outgoingRequest = $outgoingRequests->firstWhere('receiver_id', $user->id);
                  @endphp

                  <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] p-4 shadow-sm">
                    <a href="{{ route('members.show', $user->id) }}" class="flex items-start gap-4 group">
                      <img src="{{ $avatar }}" alt="{{ $name }} avatar" class="h-14 w-14 rounded-xl object-cover" />
                      <div class="min-w-0 flex-1">
                        <h3 class="truncate text-base font-semibold text-brand-blue-light dark:text-white group-hover:underline">{{ $name }}</h3>
                        <p class="truncate text-sm text-text-muted-light dark:text-text-muted-dark">{{ $user->email }}</p>
                        @if (! empty($user->username))
                          <p class="truncate text-xs text-text-muted-light/80 dark:text-text-muted-dark/80">@{{ $user->username }}</p>
                        @endif
                      </div>
                    </a>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                      @if ($isFriend)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                          Friends
                        </span>
                        <form method="POST" action="{{ route('friends.unfriend', $user->id) }}">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold text-text-light dark:text-text-dark hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-sm">person_remove</span>
                            Unfriend
                          </button>
                        </form>
                      @elseif ($incomingRequest)
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                          Friend request received
                        </span>
                        <form method="POST" action="{{ route('friends.requests.accept', $incomingRequest->id) }}">
                          @csrf
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-brand-blue-light px-3 py-1.5 text-xs font-semibold text-white">
                            <span class="material-symbols-outlined text-sm">check</span>
                            Accept
                          </button>
                        </form>
                        <form method="POST" action="{{ route('friends.requests.reject', $incomingRequest->id) }}">
                          @csrf
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold text-text-light dark:text-text-dark hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-sm">close</span>
                            Reject
                          </button>
                        </form>
                      @elseif ($outgoingRequest)
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-300">
                          Request pending
                        </span>
                      @else
                        <form method="POST" action="{{ route('friends.requests.send', $user->id) }}">
                          @csrf
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-brand-blue-light px-3 py-1.5 text-xs font-semibold text-white">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                            Send Request
                          </button>
                        </form>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
          <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-xl">
            <div class="border-b border-gray-200 dark:border-gray-800 px-5 py-4">
              <h2 class="text-lg font-semibold text-brand-blue-light dark:text-white">Incoming Requests</h2>
              <p class="text-sm text-text-muted-light dark:text-text-muted-dark">People waiting for your response.</p>
            </div>
            <div class="p-5 space-y-4">
              @forelse ($incomingRequests as $friendRequest)
                @php
                  $sender = $relatedUsers->get($friendRequest->sender_id);
                  $senderName = $displayName($sender);
                  $senderAvatar = $avatarFor($sender, $senderName);
                @endphp
                <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] p-3">
                  @if ($sender)
                    <a href="{{ route('members.show', $sender->id) }}" class="flex min-w-0 flex-1 items-center gap-3 group">
                      <img src="{{ $senderAvatar }}" alt="{{ $senderName }} avatar" class="h-11 w-11 rounded-xl object-cover" />
                      <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white group-hover:underline">{{ $senderName }}</p>
                        <p class="truncate text-xs text-text-muted-light dark:text-text-muted-dark">{{ $sender->email ?? '' }}</p>
                      </div>
                    </a>
                  @else
                    <img src="{{ $senderAvatar }}" alt="{{ $senderName }} avatar" class="h-11 w-11 rounded-xl object-cover" />
                    <div class="min-w-0 flex-1">
                      <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white">{{ $senderName }}</p>
                      <p class="truncate text-xs text-text-muted-light dark:text-text-muted-dark"></p>
                    </div>
                  @endif
                  <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('friends.requests.accept', $friendRequest->id) }}">
                      @csrf
                      <button type="submit" class="rounded-full bg-brand-blue-light px-3 py-1.5 text-xs font-semibold text-white">Accept</button>
                    </form>
                    <form method="POST" action="{{ route('friends.requests.reject', $friendRequest->id) }}">
                      @csrf
                      <button type="submit" class="rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold text-text-light dark:text-text-dark">Reject</button>
                    </form>
                  </div>
                </div>
              @empty
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-4 py-8 text-center">
                  <p class="text-sm text-text-muted-light dark:text-text-muted-dark">No incoming friend requests.</p>
                </div>
              @endforelse
            </div>
          </div>

          <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-xl">
            <div class="border-b border-gray-200 dark:border-gray-800 px-5 py-4">
              <h2 class="text-lg font-semibold text-brand-blue-light dark:text-white">Outgoing Requests</h2>
              <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Requests you already sent.</p>
            </div>
            <div class="p-5 space-y-4">
              @forelse ($outgoingRequests as $friendRequest)
                @php
                  $receiver = $relatedUsers->get($friendRequest->receiver_id);
                  $receiverName = $displayName($receiver);
                  $receiverAvatar = $avatarFor($receiver, $receiverName);
                @endphp
                <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] p-3">
                  @if ($receiver)
                    <a href="{{ route('members.show', $receiver->id) }}" class="flex min-w-0 flex-1 items-center gap-3 group">
                      <img src="{{ $receiverAvatar }}" alt="{{ $receiverName }} avatar" class="h-11 w-11 rounded-xl object-cover" />
                      <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white group-hover:underline">{{ $receiverName }}</p>
                        <p class="truncate text-xs text-text-muted-light dark:text-text-muted-dark">{{ $receiver->email ?? '' }}</p>
                      </div>
                    </a>
                  @else
                    <img src="{{ $receiverAvatar }}" alt="{{ $receiverName }} avatar" class="h-11 w-11 rounded-xl object-cover" />
                    <div class="min-w-0 flex-1">
                      <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white">{{ $receiverName }}</p>
                      <p class="truncate text-xs text-text-muted-light dark:text-text-muted-dark"></p>
                    </div>
                  @endif
                  <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-300">Pending</span>
                </div>
              @empty
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-4 py-8 text-center">
                  <p class="text-sm text-text-muted-light dark:text-text-muted-dark">No outgoing friend requests.</p>
                </div>
              @endforelse
            </div>
          </div>

          <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-xl">
            <div class="border-b border-gray-200 dark:border-gray-800 px-5 py-4">
              <h2 class="text-lg font-semibold text-brand-blue-light dark:text-white">Blocked Users</h2>
              <p class="text-sm text-text-muted-light dark:text-text-muted-dark">People you've blocked. They can't message you or send friend requests.</p>
            </div>
            <div class="p-5 space-y-4">
              @forelse (($blockedUsers ?? collect()) as $blockedUser)
                @php
                  $blockedName = $displayName($blockedUser);
                  $blockedAvatar = $avatarFor($blockedUser, $blockedName);
                @endphp
                <div class="flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0b1220] p-3">
                  <a href="{{ route('members.show', $blockedUser->id) }}" class="flex min-w-0 flex-1 items-center gap-3 group">
                    <img src="{{ $blockedAvatar }}" alt="{{ $blockedName }} avatar" class="h-11 w-11 rounded-xl object-cover" />
                    <div class="min-w-0 flex-1">
                      <p class="truncate text-sm font-semibold text-brand-blue-light dark:text-white group-hover:underline">{{ $blockedName }}</p>
                      <p class="truncate text-xs text-text-muted-light dark:text-text-muted-dark">{{ $blockedUser->email ?? '' }}</p>
                    </div>
                  </a>
                  <form method="POST" action="{{ route('friends.unblock', $blockedUser->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold text-text-light dark:text-text-dark">Unblock</button>
                  </form>
                </div>
              @empty
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-4 py-8 text-center">
                  <p class="text-sm text-text-muted-light dark:text-text-muted-dark">You haven't blocked anyone.</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="q"]');
    if (!searchInput || !searchInput.form) return;

    // The native "x" clear button on a `type="search"` input fires a `search`
    // event (it also fires on Enter). Only act when it left the field empty,
    // so clearing it re-submits the form and the server drops the stale results.
    searchInput.addEventListener('search', function () {
      if (searchInput.value.trim() === '') {
        searchInput.form.submit();
      }
    });
  });
</script>
@endsection
