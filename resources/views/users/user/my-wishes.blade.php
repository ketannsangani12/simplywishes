@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - My Wishes & Donations')

@section('content')
@php
  $tabs = [
    'active' => 'My Active Wishes & Donations',
    'progress' => 'In Progress',
    'granted' => 'Granted',
    'saved' => 'My Saved Wishes & Donations',
  ];

  $activeItems = collect($activeWishes)->map(fn ($wish) => [
    'type' => 'wish',
    'id' => $wish->w_id,
    'title' => $wish->wish_title ?: 'Untitled wish',
    'subtitle' => 'Wish',
    'description' => $wish->wish_description ?: 'No description yet.',
    'image' => $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
    'link' => route('wishes.show', $wish->w_id),
    'status' => 'Active',
  ])->concat(collect($activeDonations)->map(fn ($donation) => [
    'type' => 'donation',
    'id' => $donation->id,
    'title' => $donation->title ?: 'Untitled donation',
    'subtitle' => 'Donation',
    'description' => $donation->description ?: 'No description yet.',
    'image' => $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
    'link' => route('donations.show', $donation->id),
    'status' => 'Active',
  ]))->sortByDesc('id')->values();

  $inProgressItems = collect($inProgressWishes)->map(fn ($wish) => [
    'type' => 'wish',
    'id' => $wish->w_id,
    'title' => $wish->wish_title ?: 'Untitled wish',
    'subtitle' => 'Wish',
    'description' => $wish->wish_description ?: 'No description yet.',
    'image' => $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
    'link' => route('wishes.show', $wish->w_id),
    'status' => 'In Progress',
  ])->concat(collect($inProgressDonations)->map(fn ($donation) => [
    'type' => 'donation',
    'id' => $donation->id,
    'title' => $donation->title ?: 'Untitled donation',
    'subtitle' => 'Donation',
    'description' => $donation->description ?: 'No description yet.',
    'image' => $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
    'link' => route('donations.show', $donation->id),
    'status' => 'In Progress',
  ]))->sortByDesc('id')->values();

  $grantedItems = collect($grantedWishes)->map(fn ($wish) => [
    'type' => 'wish',
    'id' => $wish->w_id,
    'title' => $wish->wish_title ?: 'Untitled wish',
    'subtitle' => 'Wish',
    'description' => $wish->wish_description ?: 'No description yet.',
    'image' => $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
    'link' => route('wishes.show', $wish->w_id),
    'status' => 'Granted',
  ])->concat(collect($grantedDonations)->map(fn ($donation) => [
    'type' => 'donation',
    'id' => $donation->id,
    'title' => $donation->title ?: 'Untitled donation',
    'subtitle' => 'Donation',
    'description' => $donation->description ?: 'No description yet.',
    'image' => $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
    'link' => route('donations.show', $donation->id),
    'status' => 'Granted',
  ]))->sortByDesc('id')->values();

  $savedItems = collect($savedWishes)->map(fn ($wish) => [
    'type' => 'wish',
    'id' => $wish->w_id,
    'title' => $wish->wish_title ?: 'Untitled wish',
    'subtitle' => 'Saved Wish',
    'description' => $wish->wish_description ?: 'No description yet.',
    'image' => $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
    'link' => route('wishes.show', $wish->w_id),
    'status' => 'Saved',
  ])->concat(collect($savedDonations)->map(fn ($donation) => [
    'type' => 'donation',
    'id' => $donation->id,
    'title' => $donation->title ?: 'Untitled donation',
    'subtitle' => 'Saved Donation',
    'description' => $donation->description ?: 'No description yet.',
    'image' => $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
    'link' => route('donations.show', $donation->id),
    'status' => 'Saved',
  ]))->sortByDesc('id')->values();

  $itemsByTab = [
    'active' => $activeItems,
    'progress' => $inProgressItems,
    'granted' => $grantedItems,
    'saved' => $savedItems,
  ];

  $currentItems = $itemsByTab[$tab] ?? $activeItems;
@endphp

<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 pb-10 space-y-8">
    <div class="flex items-center border-b border-border-light dark:border-border-dark overflow-x-auto scrollbar-hide -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 gap-1">
      @foreach($tabs as $tabKey => $tabLabel)
        <a
          class="py-3 px-4 text-sm whitespace-nowrap border-b-2 transition-colors {{ $tab === $tabKey ? 'font-semibold border-primary text-primary' : 'font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-transparent' }}"
          href="{{ route('my.wishes', ['tab' => $tabKey]) }}">
          {{ $tabLabel }}
        </a>
      @endforeach
    </div>

    @if($currentItems->isEmpty())
      <div class="rounded-2xl border border-dashed border-border-light dark:border-border-dark bg-white/70 dark:bg-surface-dark/70 px-6 py-16 text-center">
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">Nothing here yet</h2>
        <p class="mt-2 text-sm text-subtle-light dark:text-subtle-dark">
          {{ $tabs[$tab] ?? 'This section' }} is empty right now.
        </p>
      </div>
    @else
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($currentItems as $item)
          <article class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden">
            <div class="relative">
              <img alt="{{ $item['title'] }}" class="w-full h-52 object-cover" src="{{ $item['image'] }}" />
              <div class="absolute left-3 top-3">
                <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow">
                  {{ $item['subtitle'] }}
                </span>
              </div>
              <div class="absolute right-3 top-3">
                <span class="inline-flex items-center rounded-full bg-brand-blue-light/90 px-3 py-1 text-xs font-semibold text-white shadow">
                  {{ $item['status'] }}
                </span>
              </div>
            </div>
            <div class="p-4 space-y-3">
              <div>
                <h3 class="font-semibold text-text-light dark:text-text-dark">{{ $item['title'] }}</h3>
                <p class="mt-1 text-sm text-subtle-light dark:text-subtle-dark line-clamp-2">{{ $item['description'] }}</p>
              </div>
              <a class="inline-flex items-center text-emerald-600 font-semibold hover:underline" href="{{ $item['link'] }}">
                Read More
              </a>
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </section>
</main>
@endsection
