<header class="sticky top-0 z-50 bg-surface-light/80 dark:bg-surface-dark/80 backdrop-blur-sm shadow-sm">
      <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 py-4 lg:py-0 lg:h-20">
          <a aria-label="Simply Wishes homepage" class="flex items-center gap-3 min-w-[200px]" href="{{ route('home') }}">
            <svg class="w-10 h-10 text-primary" fill="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <circle class="fill-current text-[#FBBF24]" cx="50" cy="50" r="5"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-80" cx="50" cy="30" r="4"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-70" cx="70" cy="40" r="3.5"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-70" cx="30" cy="40" r="3.5"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-60" cx="60" cy="20" r="3"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-60" cx="40" cy="20" r="3"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-90" cx="80" cy="55" r="3"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-90" cx="20" cy="55" r="3"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-80" cx="65" cy="70" r="2.5"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-80" cx="35" cy="70" r="2.5"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-70" cx="50" cy="80" r="2"></circle>
            </svg>
            <span class="text-2xl font-bold font-display text-brand-blue-light dark:text-brand-blue-dark">Simply<span
                class="text-primary">Wishes</span></span>
          </a>
          <div class="hidden lg:flex items-center space-x-8">
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('home') }}">Home</a>
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('wishes.create') }}">Make a Wish</a>
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('donations.create') }}">Donate an item</a>
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('wishes.active') }}">Active Wishes &amp; Donations</a>
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('about') }}">About Us</a>
          </div>
          <div class="flex items-center gap-3 lg:gap-4 ml-auto">
            <details class="relative">
              <summary aria-label="User menu"
                class="list-none flex items-center gap-3 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-surface-light dark:bg-surface-dark cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-blue-light to-primary flex items-center justify-center text-white font-semibold">
                  K
                </div>
                <div class="flex items-center gap-2 text-left">
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">Hi, Ketan</p>
                  <span class="material-symbols-outlined text-base text-text-muted-light dark:text-text-muted-dark">arrow_drop_down</span>
                </div>
              </summary>
              <div
                class="absolute right-0 mt-2 w-72 rounded-xl shadow-xl bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 overflow-hidden">
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="inbox.html">
                  <span class="material-symbols-outlined text-brand-blue-light">inbox</span>
                  <span class="flex-1">Inbox</span>
                  <span class="ml-2 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold px-2">4</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="forum.html">
                  <span class="material-symbols-outlined text-brand-blue-light">forum</span>
                  <span class="flex-1">Forum</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="my-wishes.html">
                  <span class="material-symbols-outlined text-emerald-500">favorite</span>
                  <span class="flex-1">My Wishes &amp; Donations</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="{{ route('wishes.drafts') }}">
                  <span class="material-symbols-outlined text-emerald-500">edit_note</span>
                  <span class="flex-1">My Wish Drafts</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="{{ route('donations.drafts') }}">
                  <span class="material-symbols-outlined text-emerald-500">inventory_2</span>
                  <span class="flex-1">My Donation Drafts</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="add-happy-story.html">
                  <span class="material-symbols-outlined text-brand-blue-light">history_edu</span>
                  <span class="flex-1">Tell your story</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="#">
                  <span class="material-symbols-outlined text-brand-blue-light">auto_stories</span>
                  <span class="flex-1">My happy stories</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="my-friends.html">
                  <span class="material-symbols-outlined text-brand-blue-light">people</span>
                  <span class="flex-1">Friends</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold"
                  href="my-profile.html">
                  <span class="material-symbols-outlined text-brand-blue-light">account_circle</span>
                  <span class="flex-1">My Profile</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-800 text-text-light dark:text-text-dark font-semibold text-left">
                    <span class="material-symbols-outlined text-brand-blue-light">logout</span>
                    <span class="flex-1">Logout</span>
                  </button>
                </form>
              </div>
            </details>
            <details class="relative">
              <summary aria-label="Open menu"
                class="list-none p-2 rounded-md text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                <span class="material-symbols-outlined">menu</span>
              </summary>
              <div
                class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-700 py-2">
                <div class="hidden lg:block">
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('happy.stories') }}">Happy Stories</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('wishers.granters.donors') }}">Wishers, Granters &amp; Donors</a>

                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('about') }}">About Us</a>
                </div>
                <div class="lg:hidden">
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('home') }}">Home</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('wishes.create') }}">Make a Wish</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('donations.create') }}">Donate an item</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('wishes.active') }}">Active Wishes &amp; Donations</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('happy.stories') }}">Happy Stories</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover-bg-gray-800"
                    href="{{ route('wishers.granters.donors') }}">Wishers, Granters &amp; Donors</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover-bg-gray-800"
                    href="{{ route('about') }}">About Us</a>
                </div>
              </div>
            </details>
          </div>
        </div>
      </nav>
    </header>
