@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'My Friends - Simply Wishes')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-slate-50 to-slate-100 dark:from-background-dark dark:via-[#0f172a] dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col gap-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-sm uppercase tracking-wide text-text-muted-light dark:text-text-muted-dark">Community</p>
              <h1 class="text-3xl sm:text-4xl font-bold text-brand-blue-light dark:text-white">Friends</h1>
              <p class="text-text-muted-light dark:text-text-muted-dark mt-2">Find, manage, and celebrate the people supporting wishes.</p>
            </div>
            <button class="inline-flex items-center gap-2 px-4 py-3 bg-brand-blue-light text-white rounded-xl shadow hover:shadow-lg transition">
              <span class="material-symbols-outlined">group_add</span>
              Add new friend
            </button>
          </div>

          <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800">
            <div class="px-4 sm:px-6 pt-6">
              <div class="flex flex-wrap items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4">
                <button class="px-3 sm:px-4 py-2 rounded-full bg-brand-blue-light text-white text-sm font-semibold shadow">List</button>
                <button class="px-3 sm:px-4 py-2 rounded-full text-sm font-semibold text-text-muted-light dark:text-text-muted-dark hover:text-brand-blue-light">Active Wishes &amp; Donations</button>
                <button class="px-3 sm:px-4 py-2 rounded-full text-sm font-semibold text-text-muted-light dark:text-text-muted-dark hover:text-brand-blue-light">Granted Wishes &amp; Donations</button>
                <button class="px-3 sm:px-4 py-2 rounded-full text-sm font-semibold text-text-muted-light dark:text-text-muted-dark hover:text-brand-blue-light">In Progress</button>
              </div>
              <div class="py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative w-full sm:w-1/2 lg:w-1/3">
                  <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted-light">search</span>
                  <input class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary"
                    placeholder="Search for your friends" type="search" />
                </div>
                <div class="flex items-center gap-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                  <span class="material-symbols-outlined text-primary">info</span>
                  Keep friends curated to stay connected with trusted wishmakers.
                </div>
              </div>
            </div>

            <div class="grid lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-gray-100 dark:divide-gray-800">
              <div class="space-y-0">
                <div class="px-4 sm:px-6 py-6">
                  <div class="flex items-center gap-4">
                    <img alt="Simply Wishes avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1493810329807-1d21b6c14d38?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Simply Wishes</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Bringing joy one wish at a time.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6 bg-slate-50 dark:bg-[#0f172a]">
                  <div class="flex items-center gap-4">
                    <img alt="Roshan avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Roshan D.</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Animal lover and long-time donor.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6">
                  <div class="flex items-center gap-4">
                    <img alt="Brews avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Brews Hamilton</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Helps coordinate local drives.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6 bg-slate-50 dark:bg-[#0f172a]">
                  <div class="flex items-center gap-4">
                    <img alt="Paeonies avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1471357674240-e1a485acb3e1?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Paeonies Pink</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Shares joy with heartfelt notes.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>
              </div>

              <div class="space-y-0">
                <div class="px-4 sm:px-6 py-6">
                  <div class="flex items-center gap-4">
                    <img alt="Simply Wishes avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1493810329807-1d21b6c14d38?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Simply Wishes</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Bringing joy one wish at a time.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6 bg-slate-50 dark:bg-[#0f172a]">
                  <div class="flex items-center gap-4">
                    <img alt="Roshan avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Roshan D.</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Animal lover and long-time donor.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6">
                  <div class="flex items-center gap-4">
                    <img alt="Brews avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Brews Hamilton</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Helps coordinate local drives.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>

                <div class="px-4 sm:px-6 py-6 bg-slate-50 dark:bg-[#0f172a]">
                  <div class="flex items-center gap-4">
                    <img alt="Paeonies avatar" class="w-16 h-16 rounded-xl object-cover shadow" src="https://images.unsplash.com/photo-1471357674240-e1a485acb3e1?auto=format&fit=crop&w=200&q=80" />
                    <div class="flex-1">
                      <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Paeonies Pink</h3>
                      <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Shares joy with heartfelt notes.</p>
                    </div>
                    <details class="relative ml-auto">
                      <summary aria-label="Friend options"
                        class="list-none p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-text-muted-light dark:text-text-muted-dark cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined text-2xl leading-none">more_horiz</span>
                      </summary>
                      <div class="absolute right-0 top-full mt-2 w-40 z-20 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark shadow-lg py-1">
                        <button class="w-full text-left px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800">Unfriend</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-800/40">Report</button>
                      </div>
                    </details>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection
