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
              href="{{ route('wishes.active') }}">Active Wishes & Donations</a>
            <a class="text-base font-medium text-text-light dark:text-text-dark hover:text-primary transition-colors"
              href="{{ route('about') }}">About Us</a>
          </div>
          <div class="flex items-center gap-3 lg:gap-4 ml-auto">
            <a href="{{ route('signup') }}"
              class="hidden sm:inline-block px-5 py-2.5 text-sm font-semibold bg-primary/20 text-brand-blue-light dark:text-primary rounded-lg hover:bg-primary/30 transition-colors">Signup</a>
            <a href="{{ route('login') }}"
              class="hidden sm:inline-block px-5 py-2.5 text-sm font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg hover:opacity-90 transition-opacity">Log
              In</a>
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
                  href="create-donation.html">Donate an item</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="active-wishes.html">Active Wishes &amp; Donations</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('happy.stories') }}">Happy Stories</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('wishers.granters.donors') }}">Wishers, Granters &amp; Donors</a>
                  <a class="block px-4 py-2 text-sm text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800"
                    href="{{ route('about') }}">About Us</a>
                </div>
              </div>
            </details>
          </div>
        </div>
      </nav>
    </header>
