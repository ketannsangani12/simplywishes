@extends('layouts.app')

@section('title', 'Simply Wishes - Granting Wishes &amp; Making Dreams Come True')

@php
  $personName = function ($user, $fallback = 'Member') {
      if (! $user) {
          return $fallback;
      }

      $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));

      return $name !== '' ? $name : ($user->name ?: $fallback);
  };

  $personAvatar = function ($user, $name) {
      if (! $user?->profile_image) {
          return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=E2E8F0&color=0F172A';
      }

      return filter_var($user->profile_image, FILTER_VALIDATE_URL) ? $user->profile_image : asset($user->profile_image);
  };

  $wishImage = fn ($wish) => $wish->primary_image
      ? (filter_var($wish->primary_image, FILTER_VALIDATE_URL) ? $wish->primary_image : asset($wish->primary_image))
      : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';

  $donationImage = fn ($donation) => $donation->image
      ? (filter_var($donation->image, FILTER_VALIDATE_URL) ? $donation->image : asset($donation->image))
      : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80';

  $storyImage = fn ($story) => $story->story_image
      ? (filter_var($story->story_image, FILTER_VALIDATE_URL) ? $story->story_image : asset($story->story_image))
      : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=900&q=80';

  $forumImage = function ($post) {
      $image = $post->e_image ?: $post->article_image;

      if (! $image) {
          return $post->is_video_only
              ? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'
              : 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=900&q=80';
      }

      return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
  };
@endphp

@section('content')
<main class="flex-grow">
  <section class="relative overflow-hidden py-20 md:py-32 bg-surface-light dark:bg-surface-dark">
    <div class="absolute inset-0 opacity-10 dark:opacity-20">
      <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-primary/30 rounded-full filter blur-3xl"></div>
      <div class="absolute bottom-[-50px] right-[-50px] w-72 h-72 bg-brand-blue-light/20 dark:bg-brand-blue-dark/20 rounded-full filter blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center">
        <h1 class="font-display text-4xl sm:text-5xl md:text-7xl font-bold text-brand-blue-light dark:text-white leading-tight">
          A single wish can <span class="text-primary">spark a universe</span> of joy.
        </h1>
        <p class="mt-6 max-w-2xl mx-auto text-lg text-text-muted-light dark:text-text-muted-dark">
          Join us in creating life-changing wish experiences for children with critical illnesses. Your support brings hope, strength, and happiness to them and their families.
        </p>
      </div>
    </div>
  </section>

  @php
    $sections = [
      [
        'title' => 'Current Wishes',
        'description' => 'Active wishes waiting for the right person to make them happen.',
        'items' => $currentWishes ?? collect(),
        'empty' => 'No current wishes available yet.',
        'seeAll' => route('wishes.active'),
        'seeAllLabel' => 'See All Wishes',
        'type' => 'wish',
      ],
      [
        'title' => 'Current Donations',
        'description' => 'Items and support opportunities that are currently open.',
        'items' => $currentDonations ?? collect(),
        'empty' => 'No current donations available yet.',
        'seeAll' => route('wishes.active') . '#current-donations',
        'seeAllLabel' => 'See All Donations',
        'type' => 'donation',
      ],
      [
        'title' => 'Granted Wishes & Donations',
        'description' => 'Recent granted moments from our community.',
        'items' => $grantedItems ?? collect(),
        'empty' => 'No granted wishes or donations yet.',
        'seeAll' => route('wishes.active') . '#granted',
        'seeAllLabel' => 'See Granted Items',
        'type' => 'granted',
      ],
      [
        'title' => "Wisher's Happy Stories",
        'description' => 'Stories written by wish creators after their wishes were granted.',
        'items' => $happyStories ?? collect(),
        'empty' => 'No happy stories yet.',
        'seeAll' => route('happy.stories'),
        'seeAllLabel' => 'See All Stories',
        'type' => 'story',
      ],
      [
        'title' => 'Forum',
        'description' => 'The latest conversations and contributions from the community.',
        'items' => $forumPosts ?? collect(),
        'empty' => 'No forum posts yet.',
        'seeAll' => route('forum'),
        'seeAllLabel' => 'Visit Forum',
        'type' => 'forum',
      ],
    ];
  @endphp

  @foreach($sections as $section)
    <section class="py-16 md:py-20 {{ $loop->even ? 'bg-surface-light dark:bg-surface-dark' : 'bg-background-light dark:bg-background-dark' }}">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
          <div>
            <h2 class="text-3xl sm:text-4xl font-bold font-display text-brand-blue-light dark:text-white">{{ $section['title'] }}</h2>
            <p class="mt-3 max-w-2xl text-text-muted-light dark:text-text-muted-dark">{{ $section['description'] }}</p>
          </div>
          <a class="inline-flex items-center gap-2 px-5 py-3 font-semibold text-primary border-2 border-primary rounded-lg hover:bg-primary hover:text-brand-blue-light transition-colors self-start md:self-auto" href="{{ $section['seeAll'] }}">
            {{ $section['seeAllLabel'] }}
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>

        @if(($section['items'] ?? collect())->isEmpty())
          <div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light dark:bg-surface-dark dark:border-border-dark">
            {{ $section['empty'] }}
          </div>
        @else
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($section['items']->take(3) as $item)
              @php
                $isWish = $section['type'] === 'wish';
                $isDonation = $section['type'] === 'donation';
                $isGranted = $section['type'] === 'granted';
                $isStory = $section['type'] === 'story';
                $isForum = $section['type'] === 'forum';

                $title = match (true) {
                    $isWish => $item->wish_title ?: 'Untitled wish',
                    $isDonation => $item->title ?: 'Untitled donation',
                    $isGranted && $item instanceof \App\Models\Wish => $item->wish_title ?: 'Untitled wish',
                    $isGranted && $item instanceof \App\Models\Donation => $item->title ?: 'Untitled donation',
                    $isStory => $item->wish?->wish_title ?: 'Happy Story',
                    $isForum => $item->e_title ?: 'Forum post',
                    default => 'Item',
                };

                $summary = match (true) {
                    $isWish => $item->wish_description ?: 'No description available.',
                    $isDonation => $item->description ?: 'No description available.',
                    $isGranted && $item instanceof \App\Models\Wish => $item->wish_description ?: 'No description available.',
                    $isGranted && $item instanceof \App\Models\Donation => $item->description ?: 'No description available.',
                    $isStory => $item->story_text ?: 'No story text available.',
                    $isForum => $item->description ?: $item->e_text ?: 'No details available.',
                    default => '',
                };

                $author = match (true) {
                    $isWish => $item->creator ?? null,
                    $isDonation => $item->creator ?? null,
                    $isGranted && $item instanceof \App\Models\Wish => $item->creator ?? null,
                    $isGranted && $item instanceof \App\Models\Donation => $item->creator ?? null,
                    $isStory => $item->user ?? null,
                    $isForum => $item->creator ?? null,
                    default => null,
                };

                $authorName = $personName($author, $isForum ? 'Forum Member' : 'Member');
                $authorAvatar = $personAvatar($author, $authorName);
                $link = match (true) {
                    $isWish => route('wishes.show', $item->w_id),
                    $isDonation => route('donations.show', $item->id),
                    $isGranted && $item instanceof \App\Models\Wish => route('wishes.show', $item->w_id),
                    $isGranted && $item instanceof \App\Models\Donation => route('donations.show', $item->id),
                    $isStory => route('happy.stories.show', $item->hs_id),
                    $isForum => route('forum.show', $item->e_id),
                    default => '#',
                };

                $image = match (true) {
                    $isWish => $wishImage($item),
                    $isDonation => $donationImage($item),
                    $isGranted && $item instanceof \App\Models\Wish => $wishImage($item),
                    $isGranted && $item instanceof \App\Models\Donation => $donationImage($item),
                    $isStory => $storyImage($item),
                    $isForum => $forumImage($item),
                    default => '',
                };

                $badge = match (true) {
                    $isWish => 'Current Wish',
                    $isDonation => 'Current Donation',
                    $isGranted && $item instanceof \App\Models\Wish => 'Granted Wish',
                    $isGranted && $item instanceof \App\Models\Donation => 'Granted Donation',
                    $isStory => 'Happy Story',
                    $isForum => 'Forum',
                    default => '',
                };
              @endphp
              <article class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
                <a href="{{ $link }}" class="block relative">
                  <img alt="{{ $title }}" class="w-full h-60 object-cover" src="{{ $image }}" />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                  <div class="absolute bottom-4 left-4 right-4">
                    <span class="inline-flex items-center rounded-full bg-black/30 px-3 py-1 text-xs font-semibold text-white mb-2">{{ $badge }}</span>
                    <h3 class="text-2xl font-bold text-white font-display line-clamp-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-200">{{ $authorName }}</p>
                  </div>
                </a>
                <div class="p-6 space-y-4">
                  <p class="text-text-muted-light dark:text-text-muted-dark h-20 overflow-hidden">{{ $summary }}</p>
                  <div class="flex items-center gap-2">
                    <img alt="{{ $authorName }}" class="w-8 h-8 rounded-full object-cover" src="{{ $authorAvatar }}" />
                    <span class="text-sm text-text-muted-light dark:text-text-muted-dark">{{ $authorName }}</span>
                  </div>
                  <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300" href="{{ $link }}">
                    View Details
                  </a>
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endforeach
</main>
@endsection
