@extends('layouts.app')

@php
  $isMine = request()->routeIs('my.happy.stories');
@endphp

@section('title', $isMine ? 'Simply Wishes - My Happy Stories' : 'Simply Wishes - Happy Stories')

@php
  $storyImage = fn ($story) => $story->imageUrl()
    ?: 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80';

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
        <h1 class="text-3xl font-bold text-brand-blue-light dark:text-white">{{ $isMine ? 'My Happy Stories' : 'Happy Stories' }}</h1>
        <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">
          {{ $isMine ? 'Happy stories you have shared with the community.' : 'Stories from our community whose wishes have been granted.' }}
        </p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
        <form class="flex items-center w-full sm:w-72" method="GET" action="{{ $isMine ? route('my.happy.stories') : route('happy.stories') }}">
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
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">
          {{ $isMine ? 'You haven\'t shared a happy story yet' : 'No happy stories yet' }}
        </h2>
        <p class="mt-2 text-sm text-subtle-light dark:text-subtle-dark">
          @if(!empty($searchTerm))
            No stories match your search.
          @elseif($isMine)
            Once your wish is granted, come back here and tell your happy story.
          @else
            Be the first to share a granted wish story.
          @endif
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
                  @auth
                    <button class="inline-flex items-center gap-1 js-story-like {{ in_array($story->hs_id, $likedStoryIds ?? [], true) ? 'text-rose-500 is-active' : 'hover:text-rose-500' }}" data-activity="like" data-happy-story-id="{{ $story->hs_id }}" aria-label="Like story" type="button">
                      <span class="material-symbols-outlined text-base">{{ in_array($story->hs_id, $likedStoryIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
                      <span data-like-count>{{ $storyLikeCounts[$story->hs_id] ?? 0 }}</span>
                    </button>
                  @else
                    <div class="flex items-center gap-1 text-text-muted-light dark:text-text-muted-dark">
                      <span class="material-symbols-outlined text-base">favorite_border</span>
                      <span>{{ $storyLikeCounts[$story->hs_id] ?? 0 }}</span>
                    </div>
                  @endauth
                </div>
                <span class="text-emerald-600 font-semibold whitespace-nowrap">{{ optional($story->created_at)->format('M d, Y') }}</span>
              </div>
              @if((int) $story->user_id === (int) auth()->id())
                <div class="flex items-center gap-2">
                  <a class="inline-flex items-center gap-1 rounded-lg bg-primary/15 px-3 py-2 text-sm font-semibold text-brand-blue-light hover:bg-primary/25 transition" href="{{ route('happy.stories.edit', $story->hs_id) }}">
                    <span class="material-symbols-outlined text-base">edit</span>
                    Edit
                  </a>
                  <form action="{{ route('happy.stories.destroy', $story->hs_id) }}" method="POST" onsubmit="return confirm('Delete this happy story? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition" type="submit">
                      <span class="material-symbols-outlined text-base">delete</span>
                      Delete
                    </button>
                  </form>
                </div>
              @endif
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('.js-story-like').forEach((button) => {
      button.addEventListener('click', async () => {
        const happyStoryId = button.getAttribute('data-happy-story-id');
        if (!happyStoryId) return;

        try {
          const isActive = button.classList.contains('is-active');
          const endpoint = isActive ? '{{ route('activities.destroy') }}' : '{{ route('activities.store') }}';
          const method = isActive ? 'DELETE' : 'POST';
          const res = await fetch(endpoint, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token || '',
              'Accept': 'application/json',
            },
            body: JSON.stringify({ happy_story_id: happyStoryId, type: 'like' }),
          });

          if (res.ok) {
            const icon = button.querySelector('.material-symbols-outlined');
            const count = button.querySelector('[data-like-count]');
            const currentCount = Number.parseInt(count?.textContent || '0', 10);

            button.classList.toggle('is-active', !isActive);
            button.classList.toggle('text-rose-500', !isActive);
            button.classList.toggle('hover:text-rose-500', isActive);
            if (icon) {
              icon.textContent = isActive ? 'favorite_border' : 'favorite';
            }
            if (count) {
              count.textContent = String(isActive ? Math.max(0, currentCount - 1) : currentCount + 1);
            }
          }
        } catch (e) {
          // ignore client-side errors
        }
      });
    });
  });
</script>
@endsection
