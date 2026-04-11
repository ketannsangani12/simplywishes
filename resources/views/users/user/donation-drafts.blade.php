@extends('layouts.app', ['bodyClass' => 'bg-background-light text-text-light font-sans antialiased', 'headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - My Donation Drafts')

@section('content')
<main class="flex-1">
      <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h1 class="text-3xl font-display font-bold text-brand-blue-light">My Donation Drafts</h1>
            </div>
          </div>

          @if($donations->isEmpty())
            <div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
              No donation drafts yet.
            </div>
          @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach($donations as $donation)
                <article class="bg-surface-light rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                  <div class="relative">
                    @php
                      $image = $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80';
                    @endphp
                    <img alt="Donation image" class="w-full aspect-[4/3] object-cover" src="{{ $image }}" />
                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 shadow-sm">
                      <span class="material-symbols-outlined text-xs">inventory_2</span>
                      Draft
                    </span>
                  </div>
                  <div class="p-4 space-y-3">
                    <div class="space-y-1">
                      <h3 class="text-lg font-semibold text-text-light">{{ $donation->title ?: 'Untitled donation' }}</h3>
                      <p class="text-sm text-text-muted-light line-clamp-2">{{ $donation->description ?: 'No description yet.' }}</p>
                    </div>
                    <div class="flex items-center justify-between text-xs text-text-muted-light">
                      @php
                        $updated = $donation->date_updated ? \Illuminate\Support\Carbon::parse($donation->date_updated)->diffForHumans() : 'recently';
                      @endphp
                      <span>Updated {{ $updated }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-brand-blue-light hover:border-primary hover:text-primary transition-colors" type="button">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        Preview
                      </button>
                      <button class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-primary/20 text-brand-blue-light text-sm font-semibold hover:bg-primary/30 transition-colors" type="button">
                        <span class="material-symbols-outlined text-base">edit</span>
                      </button>
                      <button class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors" type="button">
                        <span class="material-symbols-outlined text-base">delete</span>
                      </button>
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          @endif
        </div>
      </section>
    </main>
@endsection
