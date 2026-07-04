@extends('layouts.app')

@section('title', 'Simply Wishes - Happy Stories')

@php
  $storyImage = function ($story) {
    $image = $story->story_image;

    if (! $image) {
      return 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80';
    }

    return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
  };

  $storyAuthor = function ($story) {
    $user = $story->user;

    if (! $user) {
      return 'Simply Wishes';
    }

    $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));

    return $name !== '' ? $name : ($user->name ?: 'Member');
  };
@endphp

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-brand-blue-light dark:text-white">Happy Stories</h1>
        <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">Stories from our community whose wishes have been granted.</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
        <form class="flex items-center w-full sm:w-72" method="GET" action="{{ route('happy.stories') }}">
          <input class="flex-1 rounded-l-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-sm px-3 py-2 focus:ring-2 focus:ring-primary/60 focus:border-primary"
            name="q" value="{{ $searchTerm ?? '' }}" placeholder="Search stories..." type="search" />
          <button class="px-3 py-2 rounded-r-lg bg-brand-blue-light text-white hover:opacity-90 transition" type="submit">
            <span class="material-symbols-outlined text-base">search</span>
          </button>
        </form>
        <a class="px-4 py-2 rounded-lg bg-emerald-500 text-white font-semibold shadow hover:shadow-md transition" href="{{ route('happy.stories.create') }}">
          Tell Your Happy Story
        </a>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    @if(session('status'))
      <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
        {{ session('status') }}
      </div>
    @endif

    @if(($stories ?? collect())->isEmpty())
      <div class="rounded-2xl border border-dashed border-border-light dark:border-border-dark bg-white/70 dark:bg-surface-dark/70 px-6 py-16 text-center">
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">No happy stories yet</h2>
        <p class="mt-2 text-sm text-subtle-light dark:text-subtle-dark">
          {{ !empty($searchTerm) ? 'No stories match your search.' : 'Be the first to share a granted wish story.' }}
        </p>
      </div>
    @else
      <div class="grid gap-4 sm:gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @foreach($stories as $story)
          @php
            $authorName = $storyAuthor($story);
          @endphp
          <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden flex flex-col transition hover:-translate-y-0.5 hover:shadow-md">
            <a class="block relative" href="{{ route('happy.stories.show', $story->hs_id) }}">
              <img alt="Happy story image" class="w-full h-48 object-cover" src="{{ $storyImage($story) }}" />
            </a>
            <div class="p-4 sm:p-5 space-y-3 flex-1 flex flex-col">
              <h3 class="text-lg font-semibold text-amber-600 line-clamp-2">{{ $story->wish?->wish_title ?: 'Happy Story' }}</h3>
              <div class="flex items-center gap-2">
                <img alt="{{ $authorName }}" class="h-9 w-9 rounded-full object-cover" src="{{ $story->user?->profile_image ? (filter_var($story->user->profile_image, FILTER_VALIDATE_URL) ? $story->user->profile_image : asset($story->user->profile_image)) : 'https://ui-avatars.com/api/?name=' . urlencode($authorName) . '&background=E2E8F0&color=0F172A' }}" />
                <div>
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">{{ $authorName }}</p>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
                </div>
              </div>
              <p class="text-sm text-text-light dark:text-text-dark line-clamp-2">{{ $story->story_text }}</p>
              <div class="flex items-center justify-between gap-4 text-sm text-text-muted-light dark:text-text-muted-dark mt-auto pt-1">
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-1 text-emerald-600"><span class="material-symbols-outlined text-base">favorite</span><span>Story</span></div>
                </div>
                <span class="text-emerald-600 font-semibold whitespace-nowrap">{{ optional($story->created_at)->format('M d, Y') }}</span>
              </div>
              <a class="inline-flex items-center gap-1 text-sm font-semibold text-brand-blue-light hover:underline" href="{{ route('happy.stories.show', $story->hs_id) }}">
                View story
                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
              </a>
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </section>
</main>
@endsection
