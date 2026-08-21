@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', $name . ' - Profile')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <div class="max-w-4xl mx-auto space-y-6">

      @if(session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
          {{ session('status') }}
        </div>
      @endif

      <!-- Header card -->
      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="h-28 bg-gradient-to-r from-brand-blue-light to-brand-blue-light/70"></div>
        <div class="px-6 pb-6 pt-4">
          <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="relative self-start sm:self-auto">
              <img src="{{ $avatar }}" alt="{{ $name }}"
                class="-mt-18 w-28 h-28 rounded-2xl object-cover border-4 border-surface-light dark:border-surface-dark shadow-lg bg-gray-100" />
              <span class="absolute bottom-2 right-2 inline-flex h-4 w-4 rounded-full border-2 border-white {{ $isOnline ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
            </div>
            <div class="min-w-0 flex-1 sm:pb-2">
              <h1 class="text-2xl font-semibold leading-tight text-text-light dark:text-text-dark break-words">{{ $name }}</h1>
              <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-sm text-text-muted-light dark:text-text-muted-dark">
                <span class="inline-flex items-center gap-1.5">
                  <span class="inline-flex h-2 w-2 rounded-full {{ $isOnline ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                  <span class="font-semibold {{ $isOnline ? 'text-emerald-700' : '' }}">{{ $isOnline ? 'Online' : 'Offline' }}</span>
                </span>
                @if($location)
                  <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">location_on</span>{{ $location }}
                  </span>
                @endif
                @if($member->created_at)
                  <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">calendar_month</span>Member since {{ $member->created_at->format('M Y') }}
                  </span>
                @endif
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap items-center gap-3 mt-5">
            @if($isSelf)
              <a href="{{ route('profile.edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold shadow hover:shadow-md">
                <span class="material-symbols-outlined text-base">edit</span> Edit Profile
              </a>
            @elseif($isBlockedByMe)
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-text-muted-light dark:text-text-muted-dark text-sm font-semibold">
                <span class="material-symbols-outlined text-base">block</span> You blocked this user
              </span>
              <form method="POST" action="{{ route('friends.unblock', $member->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">
                  Unblock
                </button>
              </form>
            @else
              @unless($isBlockingMe)
                <a href="{{ route('inbox', ['user' => $member->id]) }}"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold shadow hover:shadow-md">
                  <span class="material-symbols-outlined text-base">chat</span> Message
                </a>

                @if($isFriend)
                  <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm font-semibold">
                    <span class="material-symbols-outlined text-base">how_to_reg</span> Friends
                  </span>
                @elseif($requestSent)
                  <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-text-muted-light dark:text-text-muted-dark text-sm font-semibold">
                    <span class="material-symbols-outlined text-base">schedule</span> Request sent
                  </span>
                @elseif($requestReceived)
                  <a href="{{ route('my.friends') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined text-base">group_add</span> Respond to request
                  </a>
                @else
                  <form method="POST" action="{{ route('friends.requests.send', $member->id) }}">
                    @csrf
                    <button type="submit"
                      class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">
                      <span class="material-symbols-outlined text-base">person_add</span> Add Friend
                    </button>
                  </form>
                @endif
              @endunless

              <form method="POST" action="{{ route('friends.block', $member->id) }}" onsubmit="return confirm('Block {{ $name }}? They will be removed as a friend and won\'t be able to message you or send friend requests.');">
                @csrf
                <button type="submit"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">
                  <span class="material-symbols-outlined text-base">block</span> Block
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @php
          $statCards = [
            ['label' => 'Wishes', 'value' => $stats['wishes'], 'icon' => 'auto_awesome'],
            ['label' => 'Granted', 'value' => $stats['granted'], 'icon' => 'volunteer_activism'],
            ['label' => 'Donations', 'value' => $stats['donations'], 'icon' => 'redeem'],
            ['label' => 'Happy Stories', 'value' => $stats['stories'], 'icon' => 'sentiment_very_satisfied'],
            ['label' => 'Friends', 'value' => $stats['friends'], 'icon' => 'group'],
          ];
        @endphp
        @foreach($statCards as $card)
          <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-5 text-center">
            <span class="material-symbols-outlined text-brand-blue-light dark:text-brand-blue-dark">{{ $card['icon'] }}</span>
            <p class="mt-1 text-2xl font-bold text-text-light dark:text-text-dark">{{ $card['value'] }}</p>
            <p class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $card['label'] }}</p>
          </div>
        @endforeach
      </div>

      <!-- About -->
      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">About</h2>
        @if(trim((string) $member->about) !== '')
          <p class="mt-3 text-sm text-text-muted-light dark:text-text-muted-dark leading-relaxed whitespace-pre-line">{{ $member->about }}</p>
        @else
          <p class="mt-3 text-sm text-text-muted-light dark:text-text-muted-dark italic">This member hasn't added a bio yet.</p>
        @endif
      </div>

    </div>
  </section>
</main>
@endsection
