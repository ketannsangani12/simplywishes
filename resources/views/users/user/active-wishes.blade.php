@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Active Wishes &amp; Donations')

@section('content')
<main class="flex-1 p-4 pt-24 md:p-8 md:pt-0">
<div class="container mx-auto space-y-8 md:space-y-12">
<div class="flex items-center overflow-x-auto scrollbar-hide gap-1 border-b border-border-light dark:border-border-dark -mx-4 px-4 md:mx-0 md:px-0" data-tabs>
<a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" data-tab="most-popular" href="#">Most Popular</a>
<a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" data-tab="granted" href="#">Granted</a>
<a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" data-tab="in-progress" href="#">In Progress</a>
<a class="py-3 px-4 text-sm font-semibold border-b-2 border-primary text-primary whitespace-nowrap" data-tab="current-wishes" href="#">Current Wishes</a>
<a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" data-tab="current-donations" href="#">Current Donations</a>
</div>
<div class="flex flex-wrap justify-end mt-2 gap-3">
<label class="relative w-full md:w-72">
<span class="absolute inset-y-0 left-3 flex items-center text-subtle-light dark:text-subtle-dark material-icons text-base">search</span>
<input class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm text-text-light dark:text-text-dark placeholder:text-subtle-light dark:placeholder:text-subtle-dark shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/70 focus:border-primary" placeholder="Search wishes or donations" type="search"/>
</label>
<button class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-primary text-brand-blue-light font-semibold text-sm shadow-sm hover:brightness-95 transition focus:outline-none focus:ring-2 focus:ring-primary/70 focus:ring-offset-2 focus:ring-offset-surface-light dark:focus:ring-offset-surface-dark">
<span class="material-icons text-base">search</span>
<span class="ml-2">Search</span>
</button>
</div>
<div class="space-y-12">
<div class="hidden" data-panel="most-popular">
@if($wishes->isEmpty())
<div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
No wishes available yet.
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($wishes as $wish)
@php
  $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';
@endphp
<div class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300" data-wish-type="{{ (int) $wish->non_pay_option === 1 ? 'non-financial' : 'financial' }}">
<div class="relative">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block">
<img alt="Wish image" class="w-full aspect-square object-cover max-h-48" src="{{ $image }}"/>
</a>
<div class="absolute top-3 right-3 flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-wish-id="{{ $wish->w_id }}" aria-label="Save wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-wish-id="{{ $wish->w_id }}" aria-label="Like wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
</button>
<div class="relative">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-wish-id="{{ $wish->w_id }}" data-wish-title="{{ $wish->wish_title ?: 'Wish' }}" aria-label="Share wish" type="button">
<span class="material-icons !text-base">share</span>
</button>
<div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
<div class="flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
<path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
<path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
<circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
</svg>
</button>
</div>
</div>
</div>
</div>
</div>
<div class="p-4 space-y-3">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block hover:underline">
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">{{ $wish->wish_title ?: 'Untitled wish' }}</h3>
</a>
@php
  $creator = $userMap[$wish->wished_by] ?? null;
  $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Wish Creator');
  $creatorImage = $creator && $creator->profile_image ? asset($creator->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
@endphp
<div class="flex items-center gap-2">
<img alt="{{ $creatorName }}" class="w-7 h-7 rounded-full object-cover" src="{{ $creatorImage }}"/>
<span class="text-sm text-text-muted-light">{{ $creatorName }}</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div>
<div class="hidden" data-panel="granted">
@if($grantedWishes->isEmpty())
<div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
No granted wishes yet.
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($grantedWishes as $wish)
@php
  $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';
@endphp
<div class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300" data-wish-type="{{ (int) $wish->non_pay_option === 1 ? 'non-financial' : 'financial' }}">
<div class="relative">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block">
<img alt="Wish image" class="w-full aspect-square object-cover max-h-48" src="{{ $image }}"/>
</a>
<div class="absolute top-3 right-3 flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-wish-id="{{ $wish->w_id }}" aria-label="Save wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-wish-id="{{ $wish->w_id }}" aria-label="Like wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
</button>
<div class="relative">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-wish-id="{{ $wish->w_id }}" data-wish-title="{{ $wish->wish_title ?: 'Wish' }}" aria-label="Share wish" type="button">
<span class="material-icons !text-base">share</span>
</button>
<div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
<div class="flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
<path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
<path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
<circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
</svg>
</button>
</div>
</div>
</div>
</div>
</div>
<div class="p-4 space-y-3">
<div class="flex items-center justify-between mb-2">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block hover:underline">
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">{{ $wish->wish_title ?: 'Untitled wish' }}</h3>
</a>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Granted</span>
</div>
@php
  $creator = $userMap[$wish->wished_by] ?? null;
  $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Wish Creator');
  $creatorImage = $creator && $creator->profile_image ? asset($creator->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
@endphp
<div class="flex items-center gap-2">
<img alt="{{ $creatorName }}" class="w-7 h-7 rounded-full object-cover" src="{{ $creatorImage }}"/>
<span class="text-sm text-text-muted-light">{{ $creatorName }}</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div>
<div class="hidden" data-panel="in-progress">
@if($inProgressWishes->isEmpty())
<div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
No in-progress wishes yet.
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($inProgressWishes as $wish)
@php
  $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';
@endphp
<div class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300" data-wish-type="{{ (int) $wish->non_pay_option === 1 ? 'non-financial' : 'financial' }}">
<div class="relative">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block">
<img alt="Wish image" class="w-full aspect-square object-cover max-h-48" src="{{ $image }}"/>
</a>
<div class="absolute top-3 right-3 flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-wish-id="{{ $wish->w_id }}" aria-label="Save wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-wish-id="{{ $wish->w_id }}" aria-label="Like wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
</button>
<div class="relative">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-wish-id="{{ $wish->w_id }}" data-wish-title="{{ $wish->wish_title ?: 'Wish' }}" aria-label="Share wish" type="button">
<span class="material-icons !text-base">share</span>
</button>
<div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
<div class="flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
<path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
<path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
<circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
</svg>
</button>
</div>
</div>
</div>
</div>
</div>
<div class="p-4 space-y-3">
<div class="flex items-center justify-between mb-2">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block hover:underline">
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">{{ $wish->wish_title ?: 'Untitled wish' }}</h3>
</a>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">In Progress</span>
</div>
@php
  $creator = $userMap[$wish->wished_by] ?? null;
  $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Wish Creator');
  $creatorImage = $creator && $creator->profile_image ? asset($creator->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
@endphp
<div class="flex items-center gap-2">
<img alt="{{ $creatorName }}" class="w-7 h-7 rounded-full object-cover" src="{{ $creatorImage }}"/>
<span class="text-sm text-text-muted-light">{{ $creatorName }}</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div>
<div data-panel="current-wishes">
<div class="flex justify-end mb-4">
<label class="text-sm text-text-muted-light flex items-center gap-2">
<span>Wish Type</span>
<select id="wish-type-filter" class="rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm text-text-light dark:text-text-dark px-3 py-2">
<option value="all">All</option>
<option value="financial">Financial</option>
<option value="non-financial">Non-financial</option>
</select>
</label>
</div>
@if($wishes->isEmpty())
<div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
No current wishes available yet.
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($wishes as $wish)
@php
  $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';
@endphp
<div class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300" data-wish-type="{{ (int) $wish->non_pay_option === 1 ? 'non-financial' : 'financial' }}">
<div class="relative">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block">
<img alt="Wish image" class="w-full aspect-square object-cover max-h-48" src="{{ $image }}"/>
</a>
<div class="absolute top-3 right-3 flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-wish-id="{{ $wish->w_id }}" aria-label="Save wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($wish->w_id, $favWishIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-wish-id="{{ $wish->w_id }}" aria-label="Like wish" type="button">
<span class="material-icons !text-base {{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($wish->w_id, $likeWishIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
</button>
<div class="relative">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-wish-id="{{ $wish->w_id }}" data-wish-title="{{ $wish->wish_title ?: 'Wish' }}" aria-label="Share wish" type="button">
<span class="material-icons !text-base">share</span>
</button>
<div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
<div class="flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
<path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
<path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
<circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
</svg>
</button>
</div>
</div>
</div>
</div>
</div>
<div class="p-4 space-y-3">
<a href="{{ route('wishes.show', $wish->w_id) }}" class="block hover:underline">
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">{{ $wish->wish_title ?: 'Untitled wish' }}</h3>
</a>
@php
  $creator = $userMap[$wish->wished_by] ?? null;
  $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Wish Creator');
  $creatorImage = $creator && $creator->profile_image ? asset($creator->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
@endphp
<div class="flex items-center gap-2">
<img alt="{{ $creatorName }}" class="w-7 h-7 rounded-full object-cover" src="{{ $creatorImage }}"/>
<span class="text-sm text-text-muted-light">{{ $creatorName }}</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div>
<div class="hidden" data-panel="current-donations">
<div class="flex justify-end mb-4">
<label class="text-sm text-text-muted-light flex items-center gap-2">
<span>Donation Type</span>
<select id="donation-type-filter" class="rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm text-text-light dark:text-text-dark px-3 py-2">
<option value="all">All</option>
<option value="financial">Financial</option>
<option value="non-financial">Non-financial</option>
</select>
</label>
</div>
@if(($donations ?? collect())->isEmpty())
<div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
No current donations available yet.
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($donations as $donation)
@php
  $image = $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80';
@endphp
<div class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300" data-donation-type="{{ (int) $donation->non_pay_option === 1 ? 'non-financial' : 'financial' }}">
<div class="relative">
<a href="{{ route('donations.show', $donation->id) }}" class="block">
<img alt="Donation image" class="w-full aspect-square object-cover max-h-48" src="{{ $image }}"/>
</a>
<div class="absolute top-3 right-3 flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($donation->id, $favDonationIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-donation-id="{{ $donation->id }}" aria-label="Save donation" type="button">
<span class="material-icons !text-base {{ in_array($donation->id, $favDonationIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($donation->id, $favDonationIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
</button>
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-donation-id="{{ $donation->id }}" aria-label="Like donation" type="button">
<span class="material-icons !text-base {{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
</button>
<div class="relative">
<button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-donation-id="{{ $donation->id }}" data-donation-title="{{ $donation->title ?: 'Donation' }}" aria-label="Share donation" type="button">
<span class="material-icons !text-base">share</span>
</button>
<div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
<div class="flex items-center gap-2">
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
<svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
</button>
<button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
<path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
<path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
<circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
</svg>
</button>
</div>
</div>
</div>
</div>
<div class="p-4 space-y-3">
<a href="{{ route('donations.show', $donation->id) }}" class="block hover:underline">
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">{{ $donation->title ?: 'Untitled donation' }}</h3>
</a>
@php
  $creator = $userMap[$donation->created_by] ?? null;
  $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
  $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Donation Creator');
  $creatorImage = $creator && $creator->profile_image ? asset($creator->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
  $donationType = (int) $donation->non_pay_option === 1 ? 'Non-financial' : 'Financial';
@endphp
<div class="flex items-center gap-2">
<img alt="{{ $creatorName }}" class="w-7 h-7 rounded-full object-cover" src="{{ $creatorImage }}"/>
<span class="text-sm text-text-muted-light">{{ $creatorName }}</span>
</div>
<div class="text-xs font-medium text-text-muted-light">
  Donation Type: <span class="text-text-light">{{ $donationType }}</span>
</div>
</div>
</div>
@endforeach
</div>
@endif
</div>
</div>
</div>
</main>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[data-tabs] [data-tab]');
    const panels = document.querySelectorAll('[data-panel]');
    if (!tabs.length || !panels.length) return;

    const setActive = (tabKey) => {
      tabs.forEach((tab) => {
        const isActive = tab.getAttribute('data-tab') === tabKey;
        tab.classList.toggle('font-semibold', isActive);
        tab.classList.toggle('text-primary', isActive);
        tab.classList.toggle('border-primary', isActive);
        tab.classList.toggle('font-medium', !isActive);
        tab.classList.toggle('text-subtle-light', !isActive);
        tab.classList.toggle('dark:text-subtle-dark', !isActive);
        tab.classList.toggle('border-transparent', !isActive);
      });

      panels.forEach((panel) => {
        panel.classList.toggle('hidden', panel.getAttribute('data-panel') !== tabKey);
      });
    };

    tabs.forEach((tab) => {
      tab.addEventListener('click', (event) => {
        event.preventDefault();
        setActive(tab.getAttribute('data-tab'));
      });
    });

    setActive('current-wishes');

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    document.querySelectorAll('.js-activity').forEach((button) => {
      button.addEventListener('click', async () => {
        const wishId = button.getAttribute('data-wish-id');
        const donationId = button.getAttribute('data-donation-id');
        const type = button.getAttribute('data-activity');
        if ((!wishId && !donationId) || !type) return;
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
            body: JSON.stringify({ wish_id: wishId || null, donation_id: donationId || null, type }),
          });
          if (res.ok) {
            const icon = button.querySelector('.material-icons');
            if (type === 'fav') {
              button.classList.toggle('is-active', !isActive);
              button.classList.toggle('ring-2', !isActive);
              button.classList.toggle('ring-primary/60', !isActive);
              button.classList.toggle('text-primary', !isActive);
              if (icon) {
                icon.classList.toggle('text-yellow-400', !isActive);
                icon.textContent = isActive ? 'bookmark_border' : 'bookmark';
              }
            }
            if (type === 'like') {
              button.classList.toggle('is-active', !isActive);
              button.classList.toggle('ring-2', !isActive);
              button.classList.toggle('ring-rose-400/70', !isActive);
              button.classList.toggle('text-rose-500', !isActive);
              if (icon) {
                icon.classList.toggle('text-rose-500', !isActive);
                icon.textContent = isActive ? 'favorite_border' : 'favorite';
              }
            }
          }
        } catch (e) {
          // ignore client errors for now
        }
      });
    });

    const closeShareMenus = () => {
      document.querySelectorAll('.share-menu').forEach((menu) => menu.classList.add('hidden'));
    };

    document.querySelectorAll('.js-share-btn').forEach((button) => {
      button.addEventListener('click', (event) => {
        event.stopPropagation();
        const menu = button.parentElement?.querySelector('.share-menu');
        if (!menu) return;
        const isHidden = menu.classList.contains('hidden');
        closeShareMenus();
        if (isHidden) {
          menu.classList.remove('hidden');
        }
      });
    });

    document.querySelectorAll('.share-menu [data-share-channel]').forEach((item) => {
      item.addEventListener('click', async (event) => {
        event.stopPropagation();
        const channel = item.getAttribute('data-share-channel');
        const wrapper = item.closest('.share-menu')?.parentElement;
        const shareBtn = wrapper?.querySelector('.js-share-btn');
        const wishId = shareBtn?.getAttribute('data-wish-id');
        const donationId = shareBtn?.getAttribute('data-donation-id');
        const wishTitle = shareBtn?.getAttribute('data-wish-title') || 'Wish';
        const donationTitle = shareBtn?.getAttribute('data-donation-title') || 'Donation';
        if (!wishId && !donationId) return;
        const url = wishId
          ? `${window.location.origin}/wishes/${wishId}`
          : `${window.location.origin}/donations/${donationId}`;
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(`Check out this ${wishId ? 'wish' : 'donation'}: ${wishId ? wishTitle : donationTitle}`);

        if (channel === 'facebook') {
          window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`, '_blank', 'noopener,noreferrer');
        } else if (channel === 'twitter') {
          window.open(`https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`, '_blank', 'noopener,noreferrer');
        } else if (channel === 'instagram') {
          try {
            await navigator.clipboard.writeText(url);
            alert('Link copied. Paste it in Instagram.');
          } catch (e) {
            window.prompt('Copy this link:', url);
          }
        }

        closeShareMenus();
      });
    });

    document.addEventListener('click', () => closeShareMenus());

    const applyTypeFilters = () => {
      const wishType = document.getElementById('wish-type-filter')?.value || 'all';
      const donationType = document.getElementById('donation-type-filter')?.value || 'all';

      document.querySelectorAll('[data-panel="current-wishes"] [data-wish-type]').forEach((card) => {
        const type = card.getAttribute('data-wish-type');
        const show = wishType === 'all' || type === wishType;
        card.classList.toggle('hidden', !show);
      });

      document.querySelectorAll('[data-panel="current-donations"] [data-donation-type]').forEach((card) => {
        const type = card.getAttribute('data-donation-type');
        const show = donationType === 'all' || type === donationType;
        card.classList.toggle('hidden', !show);
      });
    };

    const wishFilter = document.getElementById('wish-type-filter');
    const donationFilter = document.getElementById('donation-type-filter');
    if (wishFilter) wishFilter.addEventListener('change', applyTypeFilters);
    if (donationFilter) donationFilter.addEventListener('change', applyTypeFilters);
    applyTypeFilters();
  });
</script>
@endsection
