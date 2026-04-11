@extends('layouts.app', ['bodyClass' => 'bg-background-light text-text-light font-sans antialiased', 'headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - My Wish Drafts')

@section('content')
<main class="flex-1">
      <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h1 class="text-3xl font-display font-bold text-brand-blue-light">My Wish Drafts</h1>
            </div>
          
          </div>

          @if($wishes->isEmpty())
            <div class="rounded-xl border border-dashed border-border-light bg-white p-10 text-center text-text-muted-light">
              No wish drafts yet.
            </div>
          @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach($wishes as $wish)
                <article class="bg-surface-light rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                  <div class="relative">
                    @php
                      $image = $wish->primary_image ? asset($wish->primary_image) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80';
                    @endphp
                    <img alt="Wish image" class="w-full aspect-[4/3] object-cover" src="{{ $image }}" />
                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 shadow-sm">
                      <span class="material-symbols-outlined text-xs">edit_note</span>
                      Draft
                    </span>
                  </div>
                  <div class="p-4 space-y-3">
                    <div class="space-y-1">
                      <h3 class="text-lg font-semibold text-text-light">{{ $wish->wish_title ?: 'Untitled wish' }}</h3>
                      <p class="text-sm text-text-muted-light line-clamp-2">{{ $wish->wish_description ?: 'No description yet.' }}</p>
                    </div>
                    <div class="flex items-center justify-between text-xs text-text-muted-light">
                      @php
                        $updated = $wish->date_updated ? \Illuminate\Support\Carbon::parse($wish->date_updated)->diffForHumans() : 'recently';
                      @endphp
                      <span>Updated {{ $updated }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <a class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-brand-blue-light hover:border-primary hover:text-primary transition-colors" href="{{ route('wishes.show', $wish->w_id) }}">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        Preview
                      </a>
                      <a class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-primary/20 text-brand-blue-light text-sm font-semibold hover:bg-primary/30 transition-colors" href="{{ route('wishes.edit', $wish->w_id) }}">
                        <span class="material-symbols-outlined text-base">edit</span>
                      </a>
                      <form action="{{ route('wishes.destroy', $wish->w_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors" type="submit" onclick="return confirm('Delete this draft?');">
                          <span class="material-symbols-outlined text-base">delete</span>
                        </button>
                      </form>
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
