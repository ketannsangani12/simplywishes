@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Forum Details')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-5xl mx-auto">
          <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark mb-6">Test</h1>

          <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm p-6 sm:p-8">
            <div class="flex justify-between items-start mb-4">
              <div class="flex items-center gap-3">
                <img alt="Author avatar" class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                <div>
                  <p class="text-sm text-text-muted-light dark:text-text-muted-dark">By:</p>
                  <a class="text-base font-semibold text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="#">Brews Hamilton</a>
                  <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">Date: 06-25-2025</p>
                </div>
              </div>
              <div class="flex items-center gap-3 text-xl text-text-muted-light dark:text-text-muted-dark">
                <a class="hover:text-blue-600" href="#" aria-label="Share on Facebook"><span class="material-icons !text-base">facebook</span></a>
                <a class="hover:text-red-600" href="#" aria-label="Share on Pinterest"><span class="material-icons !text-base">push_pin</span></a>
                <a class="hover:text-sky-500" href="#" aria-label="Share on Twitter"><span class="material-icons !text-base">alternate_email</span></a>
              </div>
            </div>

            <div class="flex items-center gap-3 mb-4">
              <button class="p-2 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200" aria-label="Love">
                <span class="material-icons !text-xl">favorite</span>
              </button>
              <button class="p-2 rounded-full bg-red-100 text-red-600 hover:bg-red-200" aria-label="Report">
                <span class="material-icons !text-xl">flag</span>
              </button>
            </div>

            <p class="text-lg font-semibold mb-6">Test</p>

            <div class="w-full flex justify-center">
              <img alt="Forum post" class="max-w-full rounded-lg shadow-md border border-border-light dark:border-border-dark" src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=900&q=80" />
            </div>
          </div>

          <div class="mt-10 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl shadow-sm p-6 sm:p-8 space-y-6">
            <h2 class="text-xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
            <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
              <div class="flex items-start gap-4">
                <img alt="Current user avatar" class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80" />
                <div class="flex-1 space-y-3">
                  <textarea rows="3" class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y" placeholder="Add a comment..."></textarea>
                  <div class="flex justify-end">
                    <button class="px-5 py-2 bg-brand-blue-light text-white font-semibold rounded-lg shadow hover:opacity-90 transition">Post</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-4">
              <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark">
                <div class="flex items-start gap-4">
                  <img alt="Commenter avatar" class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80" />
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <div class="space-y-1">
                        <p class="font-semibold text-primary">Ketan S</p>
                        <div class="text-xs text-text-muted-light dark:text-text-muted-dark">June 25 2025 9:00 am</div>
                      </div>
                      <div class="flex items-center gap-2 text-text-muted-light dark:text-text-muted-dark">
                        <button class="hover:text-primary" aria-label="Edit"><span class="material-icons !text-base">edit</span></button>
                        <button class="hover:text-red-500" aria-label="Delete"><span class="material-icons !text-base">delete</span></button>
                      </div>
                    </div>
                    <p class="mt-2 text-text-light dark:text-text-dark">Great post! Thanks for sharing.</p>
                    <div class="mt-3 flex items-center gap-4 text-sm text-text-muted-light dark:text-text-muted-dark">
                      <button class="inline-flex items-center gap-1 hover:text-red-500">
                        <span class="material-icons !text-base text-red-500">favorite</span>
                        <span>Love</span>
                      </button>
                      <button class="inline-flex items-center gap-1 hover:text-primary">
                        <span class="material-icons !text-base">reply</span>
                        Reply
                      </button>
                      <button class="inline-flex items-center gap-1 hover:text-amber-500">
                        <span class="material-icons !text-base">flag</span>
                        Report
                      </button>
                      <div class="inline-flex items-center gap-1">
                        <span class="material-icons !text-base text-red-500">favorite</span>
                        <span class="text-red-500 font-semibold">0</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection
