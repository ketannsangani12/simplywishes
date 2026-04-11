@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Wishers, Granters &amp; Donors')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="max-w-6xl mx-auto space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="text-3xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Wishers, Granters &amp; Donors</h1>
            <p class="text-text-muted-light dark:text-text-muted-dark text-sm">Browse community members and find your next match.</p>
          </div>

          <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-4 sm:p-6 space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
              <div class="grid grid-cols-3 gap-2 w-full lg:w-auto lg:grid-cols-3">
                <button data-role="wishers"
                  class="role-tab px-4 py-3 rounded-lg font-semibold bg-brand-blue-light text-white shadow hover:shadow-md transition">
                  Wishers
                </button>
                <button data-role="granters"
                  class="role-tab px-4 py-3 rounded-lg font-semibold bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                  Granters
                </button>
                <button data-role="donors"
                  class="role-tab px-4 py-3 rounded-lg font-semibold bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-brand-blue-light dark:text-brand-blue-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                  Donors
                </button>
              </div>

              <div class="flex items-center w-full lg:w-80">
                <input id="searchBox" class="flex-1 rounded-l-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-sm px-3 py-2 focus:ring-2 focus:ring-primary/60 focus:border-primary"
                  placeholder="Search by name..." type="search" />
                <span class="px-3 py-2 rounded-r-lg border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-text-muted-light dark:text-text-muted-dark">
                  <span class="material-symbols-outlined text-base">search</span>
                </span>
              </div>
            </div>

            <div id="profilesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
          </div>
        </div>
      </section>
    </main>
@endsection
