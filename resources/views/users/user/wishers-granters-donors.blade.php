@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Wishers, Granters &amp; Donors')

@php
  $currentUsers = match ($tab ?? 'wishers') {
      'granters' => $granters ?? collect(),
      'donors' => $donors ?? collect(),
      default => $wishers ?? collect(),
  };

  $tabUrl = fn (string $tabName) => route('wishers.granters.donors', array_filter([
      'tab' => $tabName,
      'q' => $searchTerm ?? null,
  ], fn ($value) => $value !== null && $value !== ''));

  $userName = function ($user) {
      $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));

      return $name !== '' ? $name : ($user->name ?: 'Member');
  };

  $userAvatar = function ($user, $name) {
      if (! $user?->profile_image) {
          return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=E2E8F0&color=0F172A';
      }

      return filter_var($user->profile_image, FILTER_VALIDATE_URL) ? $user->profile_image : asset($user->profile_image);
  };
@endphp

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <div class="max-w-7xl mx-auto space-y-6">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-3xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Wishers, Granters &amp; Donors</h1>
          <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">Browse community members ranked by their activity on SimplyWishes.</p>
        </div>
        <form class="flex items-center w-full sm:w-96" method="GET" action="{{ route('wishers.granters.donors') }}">
          <input type="hidden" name="tab" value="{{ $tab ?? 'wishers' }}" />
          <input
            class="flex-1 rounded-l-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-sm px-3 py-2 focus:ring-2 focus:ring-primary/60 focus:border-primary"
            name="q"
            value="{{ $searchTerm ?? '' }}"
            placeholder="Search users..."
            type="search"
          />
          <button class="px-3 py-2 rounded-r-lg bg-brand-blue-light text-white hover:opacity-90 transition" type="submit">
            <span class="material-symbols-outlined text-base">search</span>
          </button>
        </form>
      </div>

      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-4 sm:p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <a href="{{ $tabUrl('wishers') }}" class="px-4 py-3 rounded-lg font-semibold text-center transition {{ ($tab ?? 'wishers') === 'wishers' ? 'bg-brand-blue-light text-white shadow hover:shadow-md' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            Wishers
          </a>
          <a href="{{ $tabUrl('granters') }}" class="px-4 py-3 rounded-lg font-semibold text-center transition {{ ($tab ?? 'wishers') === 'granters' ? 'bg-brand-blue-light text-white shadow hover:shadow-md' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            Granters
          </a>
          <a href="{{ $tabUrl('donors') }}" class="px-4 py-3 rounded-lg font-semibold text-center transition {{ ($tab ?? 'wishers') === 'donors' ? 'bg-brand-blue-light text-white shadow hover:shadow-md' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            Donors
          </a>
        </div>

        @if(($currentUsers ?? collect())->isEmpty())
          <div class="rounded-2xl border border-dashed border-border-light dark:border-border-dark bg-white/70 dark:bg-surface-dark/70 px-6 py-16 text-center">
            <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">No users found</h2>
            <p class="mt-2 text-sm text-subtle-light dark:text-subtle-dark">
              {{ !empty($searchTerm) ? 'No users match your search.' : 'No records available for this section yet.' }}
            </p>
          </div>
        @else
          <div class="grid gap-4 sm:gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach($currentUsers as $user)
              @php
                $name = $userName($user);
                $avatar = $userAvatar($user, $name);
                $count = (int) ($user->items_count ?? 0);
              @endphp
              <article class="rounded-2xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark shadow-sm p-5 flex items-center gap-4">
                <img src="{{ $avatar }}" alt="{{ $name }}" class="h-16 w-16 rounded-full object-cover border border-gray-200 dark:border-gray-700" />
                <div class="min-w-0 flex-1">
                  <h3 class="font-semibold text-text-light dark:text-text-dark truncate">{{ $name }}</h3>
                  <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <span>{{ $count }}</span>
                    <span>{{ ($tab ?? 'wishers') === 'wishers' ? 'wishes created' : (($tab ?? 'wishers') === 'granters' ? 'wishes granted' : 'donations created') }}</span>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </section>
</main>
@endsection
