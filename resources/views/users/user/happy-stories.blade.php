@extends('layouts.app')

@section('title', 'Simply Wishes - Happy Stories')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
      <section class="border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-brand-blue-light dark:text-white">Happy Stories</h1>
            <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">Stories from our community whose wishes have been granted.</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <div class="flex items-center w-full sm:w-72">
              <input class="flex-1 rounded-l-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-sm px-3 py-2 focus:ring-2 focus:ring-primary/60 focus:border-primary"
                placeholder="Search stories..." type="search" />
              <button class="px-3 py-2 rounded-r-lg bg-brand-blue-light text-white hover:opacity-90 transition" type="button">
                <span class="material-symbols-outlined text-base">search</span>
              </button>
            </div>
            <button class="px-4 py-2 rounded-lg bg-emerald-500 text-white font-semibold shadow hover:shadow-md transition" type="button">
              Tell Your Happy Story
            </button>
          </div>
        </div>
      </section>

      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="grid gap-4 sm:gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
          <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="relative">
              <img alt="Abstract teal and sand painting" class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80" />
            </div>
            <div class="p-4 sm:p-5 space-y-3 flex-1 flex flex-col">
              <h3 class="text-lg font-semibold text-amber-600 line-clamp-2">first wish from the android app</h3>
              <div class="flex items-center gap-2">
                <img alt="pjeones pink" class="h-9 w-9 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div>
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">pjeones pink</p>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
                </div>
              </div>
              <p class="text-sm text-text-light dark:text-text-dark line-clamp-2">Update post · Yea</p>
              <div class="flex items-center justify-between gap-4 text-sm text-text-muted-light dark:text-text-muted-dark mt-auto pt-1">
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-1 text-emerald-600"><span class="material-symbols-outlined text-base">favorite</span><span>1 Love</span></div>
                  <div class="flex items-center gap-1 text-brand-blue-light"><span class="material-symbols-outlined text-base">share</span><span>Share</span></div>
                </div>
                <a class="text-emerald-600 font-semibold hover:underline inline-flex items-center gap-1 whitespace-nowrap" href="#">Read More<span class="material-symbols-outlined text-sm">north_east</span></a>
              </div>
            </div>
          </article>

          <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="relative">
              <img alt="Merry Christmas card" class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1482517967863-00e15c9b44be?auto=format&fit=crop&w=600&q=80" />
            </div>
            <div class="p-4 sm:p-5 space-y-3 flex-1 flex flex-col">
              <h3 class="text-lg font-semibold text-amber-600 line-clamp-2">wish to get granted</h3>
              <div class="flex items-center gap-2">
                <img alt="Bel Estos" class="h-9 w-9 rounded-full object-cover" src="https://images.unsplash.com/photo-1521119989659-a83eee488004?auto=format&fit=crop&w=200&q=80" />
                <div>
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">Bel Estos</p>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
                </div>
              </div>
              <div class="flex items-center justify-between gap-4 text-sm text-text-muted-light dark:text-text-muted-dark mt-auto pt-1">
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-1 text-emerald-600"><span class="material-symbols-outlined text-base">favorite</span><span>2 Loves</span></div>
                  <div class="flex items-center gap-1 text-brand-blue-light"><span class="material-symbols-outlined text-base">share</span><span>Share</span></div>
                </div>
                <a class="text-emerald-600 font-semibold hover:underline inline-flex items-center gap-1 whitespace-nowrap" href="#">Read More<span class="material-symbols-outlined text-sm">north_east</span></a>
              </div>
            </div>
          </article>

          <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="relative">
              <img alt="Desert landscape" class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=80" />
            </div>
            <div class="p-4 sm:p-5 space-y-3 flex-1 flex flex-col">
              <h3 class="text-lg font-semibold text-amber-600 line-clamp-2">breadmaker</h3>
              <div class="flex items-center gap-2">
                <img alt="pjeones pink" class="h-9 w-9 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div>
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">pjeones pink</p>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
                </div>
              </div>
              <div class="flex items-center justify-between gap-4 text-sm text-text-muted-light dark:text-text-muted-dark mt-auto pt-1">
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-1 text-emerald-600"><span class="material-symbols-outlined text-base">favorite</span><span>1 Love</span></div>
                  <div class="flex items-center gap-1 text-brand-blue-light"><span class="material-symbols-outlined text-base">share</span><span>Share</span></div>
                </div>
                <a class="text-emerald-600 font-semibold hover:underline inline-flex items-center gap-1 whitespace-nowrap" href="#">Read More<span class="material-symbols-outlined text-sm">north_east</span></a>
              </div>
            </div>
          </article>

          <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="relative">
              <img alt="Colorful ride detail" class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1508612761958-e931d843bddc?auto=format&fit=crop&w=600&q=80" />
            </div>
            <div class="p-4 sm:p-5 space-y-3 flex-1 flex flex-col">
              <h3 class="text-lg font-semibold text-amber-600 line-clamp-2">breadmaker</h3>
              <div class="flex items-center gap-2">
                <img alt="pjeones pink" class="h-9 w-9 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div>
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">pjeones pink</p>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
                </div>
              </div>
              <div class="flex items-center justify-between gap-4 text-sm text-text-muted-light dark:text-text-muted-dark mt-auto pt-1">
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-1 text-emerald-600"><span class="material-symbols-outlined text-base">favorite</span><span>3 Loves</span></div>
                  <div class="flex items-center gap-1 text-brand-blue-light"><span class="material-symbols-outlined text-base">share</span><span>Share</span></div>
                </div>
                <a class="text-emerald-600 font-semibold hover:underline inline-flex items-center gap-1 whitespace-nowrap" href="#">Read More<span class="material-symbols-outlined text-sm">north_east</span></a>
              </div>
            </div>
          </article>
        </div>

      </section>
    </main>
@endsection
