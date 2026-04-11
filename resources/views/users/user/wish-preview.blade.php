@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Wish Detail')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-5xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-border-light dark:border-border-dark bg-slate-50/60 dark:bg-background-dark/60">
        <div class="flex items-center justify-between gap-4">
          <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark">
            {{ $wish->wish_title ?: 'Untitled wish' }}
          </h1>
          <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition" href="{{ route('wishes.active') }}">
            <span class="material-icons !text-base">arrow_back</span>
            Back
          </a>
        </div>
      </div>

      <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
          <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden bg-white dark:bg-surface-dark shadow-sm relative">
            @php
              $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=800&q=80';
            @endphp
            <img alt="Wish image" class="w-full object-cover aspect-square" src="{{ $image }}" />
            <div class="absolute top-3 right-3 flex items-center gap-2">
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition" aria-label="Save wish" type="button">
                <span class="material-icons !text-base">bookmark</span>
              </button>
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition" aria-label="Like wish" type="button">
                <span class="material-icons !text-base">favorite</span>
              </button>
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-amber-500 transition" aria-label="Report wish" type="button">
                <span class="material-icons !text-base">flag</span>
              </button>
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition" aria-label="Share wish" type="button">
                <span class="material-icons !text-base">share</span>
              </button>
            </div>
          </div>
        </div>

        <div class="lg:col-span-2 space-y-6 text-sm">
          <div>
            <p class="text-text-muted-light dark:text-text-muted-dark">Description</p>
            <p class="font-semibold">{{ $wish->wish_description ?: 'No description yet.' }}</p>
          </div>

          @php
            $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
            $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Wish Creator');
          @endphp
          @php
            $isFinancial = (int) $wish->non_pay_option !== 1;
            $deliveryType = $wish->way_of_wish ?: 'Not set';
          @endphp
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <p class="text-text-muted-light dark:text-text-muted-dark">Creator</p>
              <p class="font-semibold">{{ $creatorName }}</p>
            </div>
            <div>
              <p class="text-text-muted-light dark:text-text-muted-dark">Date - I would like my wish to be granted</p>
              <p class="font-semibold">{{ $wish->expected_date ?: 'Not set' }}</p>
            </div>
            <div>
              <p class="text-text-muted-light dark:text-text-muted-dark">Wish Type</p>
              <p class="font-semibold">{{ $isFinancial ? 'Financial' : 'Non-Financial' }}</p>
            </div>
            @if($isFinancial)
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Wish Expected Cost</p>
                <p class="font-semibold">{{ $wish->expected_cost ? '$' . number_format($wish->expected_cost, 0) : 'Not set' }}</p>
              </div>
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Financial assistance</p>
                <p class="font-semibold">{{ $wish->financial_assistance ?: 'Not set' }}</p>
              </div>
            @else
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Delivery Type</p>
                <p class="font-semibold">{{ $deliveryType }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60">
      <div class="p-6 sm:p-8 space-y-6">
        <h2 class="text-2xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
        <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-slate-200"></div>
            <div class="flex-1 space-y-3">
              <textarea rows="3" class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y" placeholder="Add a comment..."></textarea>
              <div class="flex justify-end">
                <button class="px-5 py-2 bg-brand-blue-light text-white font-semibold rounded-lg shadow hover:opacity-90 transition">Post</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
