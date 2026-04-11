@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Donation Detail')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12 mt-8">
        <div class="max-w-6xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60 overflow-hidden">
          <div class="p-6 sm:p-8 bg-slate-50/60 dark:bg-background-dark/60 border-b border-border-light dark:border-border-dark">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark">donation from website1</h1>
              <div class="flex items-center gap-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                <span class="material-icons !text-base text-amber-500">info</span>
                <span>Previewing donation</span>
              </div>
            </div>
          </div>

          <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div class="lg:col-span-1">
                <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden bg-white dark:bg-surface-dark shadow-sm">
                  <img alt="Donation preview" class="w-full object-cover aspect-[4/3]" src="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80" />
                  <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-lg">
                      <button class="p-2 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200" aria-label="Save">
                        <span class="material-icons !text-xl">bookmark</span>
                      </button>
                      <button class="p-2 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200" aria-label="Love">
                        <span class="material-icons !text-xl">favorite</span>
                      </button>
                      <button class="p-2 rounded-full bg-sky-100 text-sky-600 hover:bg-sky-200" aria-label="Share">
                        <span class="material-icons !text-xl">share</span>
                      </button>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">Open</span>
                  </div>
                </div>
              </div>

              <div class="lg:col-span-2 space-y-6">
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                  <div class="flex gap-3">
                    <span class="material-icons text-primary">badge</span>
                    <div>
                      <p class="text-text-muted-light dark:text-text-muted-dark">Name</p>
                      <a class="font-semibold text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="#">Brews Hamilton</a>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <span class="material-icons text-primary">notes</span>
                    <div>
                      <p class="text-text-muted-light dark:text-text-muted-dark">Donation Description</p>
                      <p class="font-semibold">testing</p>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <span class="material-icons text-primary">category</span>
                    <div>
                      <p class="text-text-muted-light dark:text-text-muted-dark">Donation Type</p>
                      <p class="font-semibold">Non-Financial</p>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <span class="material-icons text-primary">local_shipping</span>
                    <div>
                      <p class="text-text-muted-light dark:text-text-muted-dark">Delivery Type</p>
                      <p class="font-semibold">Online order (amazon)</p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-wrap gap-3">
                  <button class="flex-1 sm:flex-none px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow hover:bg-emerald-700 transition inline-flex items-center justify-center gap-2">
                    <span class="material-icons !text-base">volunteer_activism</span>
                    Accept This Donation
                  </button>
                  <button class="flex-1 sm:flex-none px-5 py-3 bg-amber-500 text-white font-semibold rounded-lg shadow hover:bg-amber-600 transition inline-flex items-center justify-center gap-2">
                    <span class="material-icons !text-base">send</span>
                    Send a Message
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="max-w-6xl mx-auto mt-10 bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60">
          <div class="p-6 sm:p-8 space-y-6">
            <h2 class="text-2xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
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
                        <div class="text-xs text-text-muted-light dark:text-text-muted-dark">December 1 2025 9:35 am</div>
                      </div>
                      <div class="flex items-center gap-2 text-text-muted-light dark:text-text-muted-dark">
                        <button class="hover:text-primary" aria-label="Edit"><span class="material-icons !text-base">edit</span></button>
                        <button class="hover:text-red-500" aria-label="Delete"><span class="material-icons !text-base">delete</span></button>
                      </div>
                    </div>
                    <p class="mt-2 text-text-light dark:text-text-dark">jkfjdfjkf</p>
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

              <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark">
                <div class="flex items-start gap-4">
                  <img alt="Commenter avatar" class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=200&q=80" />
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <div class="space-y-1">
                        <p class="font-semibold text-primary">Ketan S</p>
                        <div class="text-xs text-text-muted-light dark:text-text-muted-dark">December 1 2025 9:35 am</div>
                      </div>
                      <div class="flex items-center gap-2 text-text-muted-light dark:text-text-muted-dark">
                        <button class="hover:text-primary" aria-label="Edit"><span class="material-icons !text-base">edit</span></button>
                        <button class="hover:text-red-500" aria-label="Delete"><span class="material-icons !text-base">delete</span></button>
                      </div>
                    </div>
                    <p class="mt-2 text-text-light dark:text-text-dark">sjdksjksjd</p>
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
