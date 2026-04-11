@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - My Wishes & Donations')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 pb-10 space-y-8">
        <div class="flex items-center border-b border-border-light dark:border-border-dark overflow-x-auto scrollbar-hide -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 gap-1">
          <a class="py-3 px-4 text-sm font-semibold border-b-2 border-primary text-primary whitespace-nowrap" href="#">My Active Wishes &amp; Donations</a>
          <a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" href="#">In Progress</a>
          <a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" href="#">Granted</a>
          <a class="py-3 px-4 text-sm font-medium text-subtle-light dark:text-subtle-dark hover:text-primary hover:border-primary border-b-2 border-transparent transition-colors whitespace-nowrap" href="#">My Saved Wishes &amp; Donations</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <article class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden">
            <div class="relative">
              <img alt="Wish thumbnail" class="w-full h-52 object-cover" src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80" />
              <div class="absolute top-3 right-3 flex flex-col gap-2">
                <button class="p-2 rounded-full bg-yellow-400 text-white shadow hover:opacity-90" aria-label="Save"><span class="material-icons !text-base">bookmark</span></button>
                <button class="p-2 rounded-full bg-emerald-500 text-white shadow hover:opacity-90" aria-label="Love"><span class="material-icons !text-base">favorite</span></button>
                <button class="p-2 rounded-full bg-sky-500 text-white shadow hover:opacity-90" aria-label="Share"><span class="material-icons !text-base">share</span></button>
              </div>
            </div>
            <div class="p-4 space-y-3">
              <div class="flex items-center gap-2">
                <img alt="Author avatar" class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div class="leading-tight">
                  <p class="text-sm text-subtle-light dark:text-subtle-dark">Ketan S</p>
                  <p class="font-semibold text-text-light dark:text-text-dark">dfgfdg</p>
                </div>
              </div>
              <a class="text-emerald-600 font-semibold hover:underline" href="#">Read More</a>
            </div>
          </article>

          <article class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden">
            <div class="relative">
              <img alt="Wish thumbnail" class="w-full h-52 object-cover" src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80" />
              <div class="absolute top-3 right-3 flex flex-col gap-2">
                <button class="p-2 rounded-full bg-yellow-400 text-white shadow hover:opacity-90" aria-label="Save"><span class="material-icons !text-base">bookmark</span></button>
                <button class="p-2 rounded-full bg-emerald-500 text-white shadow hover:opacity-90" aria-label="Love"><span class="material-icons !text-base">favorite</span></button>
                <button class="p-2 rounded-full bg-sky-500 text-white shadow hover:opacity-90" aria-label="Share"><span class="material-icons !text-base">share</span></button>
              </div>
            </div>
            <div class="p-4 space-y-3">
              <div class="flex items-center gap-2">
                <img alt="Author avatar" class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div class="leading-tight">
                  <p class="text-sm text-subtle-light dark:text-subtle-dark">Ketan S</p>
                  <p class="font-semibold text-text-light dark:text-text-dark">test</p>
                </div>
              </div>
              <a class="text-emerald-600 font-semibold hover:underline" href="#">Read More</a>
            </div>
          </article>

          <article class="bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm overflow-hidden">
            <div class="relative">
              <img alt="Wish thumbnail" class="w-full h-52 object-cover" src="https://images.unsplash.com/photo-1500534318144-7b2db3a0fbc9?auto=format&fit=crop&w=800&q=80" />
              <div class="absolute top-3 right-3 flex flex-col gap-2">
                <button class="p-2 rounded-full bg-yellow-400 text-white shadow hover:opacity-90" aria-label="Save"><span class="material-icons !text-base">bookmark</span></button>
                <button class="p-2 rounded-full bg-emerald-500 text-white shadow hover:opacity-90" aria-label="Love"><span class="material-icons !text-base">favorite</span></button>
                <button class="p-2 rounded-full bg-sky-500 text-white shadow hover:opacity-90" aria-label="Share"><span class="material-icons !text-base">share</span></button>
              </div>
            </div>
            <div class="p-4 space-y-3">
              <div class="flex items-center gap-2">
                <img alt="Author avatar" class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div class="leading-tight">
                  <p class="text-sm text-subtle-light dark:text-subtle-dark">Ketan S</p>
                  <p class="font-semibold text-text-light dark:text-text-dark">sdjksjsj</p>
                </div>
              </div>
              <a class="text-emerald-600 font-semibold hover:underline" href="#">Read More</a>
            </div>
          </article>
        </div>
      </section>
    </main>
@endsection
