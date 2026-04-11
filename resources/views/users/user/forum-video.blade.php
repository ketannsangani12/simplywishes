@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Forum Videos')

@section('content')
<main class="flex-grow">
      <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="text-center mb-8 md:mb-12">
          <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-2">Community Forum</h1>
          <p class="text-lg text-slate-600 dark:text-slate-400">Share stories, ask questions, and connect with our community.</p>
        </div>
        <div class="mb-8 md:mb-10">
          <div class="relative max-w-2xl mx-auto">
            <input class="w-full pl-12 pr-4 py-3 rounded-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary focus:border-primary transition" placeholder="Search articles, videos, and more..." type="search" />
            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">search</span>
          </div>
        </div>
        <div class="flex justify-center border-b border-slate-200 dark:border-slate-700 mb-8 md:mb-10">
          <div class="flex space-x-4 sm:space-x-8">
            <button class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">article</span>
              Articles
            </button>
            <button class="py-3 px-2 text-primary border-b-2 border-primary font-semibold text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">videocam</span>
              Videos
            </button>
            <button class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">volunteer_activism</span>
              Contribute
            </button>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="Tree in a field during a beautiful sunset" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgjLr9Yy-8UC_ItkFDDpG8IFy9AO5itQKzdZt_3XiPrGQaY05gfSf_5HB2bVvnr1bUTYL7xl7tM3D_IbK6KPJf2TIHIQme-G-hYl0J1ss8nj-AearirxImtTYsKFQcq0q6Qs23XIToJ-oCNisZIiy23vPxVJViHnXnov7ydeytj1De0AESsRV8iHXwUFB_1ppVeCSzK3As42pXvEXAMfw_T9maO_8YHbmb3QaX8qKIWK4a-NoSDkChD7JwhliH3J3jmohRzaO29sDf" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Sunset of Gratitude</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">Heartfelt thank you messages from wish recipients and their families.</p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="A young girl smiling brightly while holding a teddy bear" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCadECJkb5EEjovfwA0pmiVstQG3ovBtWQXSoykxOOK1wZVSN9thVtM9j-q9k2kX3Y9KlliB5qYR_hdrIxdYo6pd3VNIceAPEW43kqeJ79QmxLZwYYdvulP2l9wRGnQfdXzS48Refd3w9HMiqpHLE7nDbOyXcrkBXozfQgt7ShaulWhflJuDWbInOL-mdfJFklM82t3ewHSeB5w6i_TUAemzbhqC1OQ7DyD1Fs7-CF_QPxKE5hr6mUx80TT5zRLzfmFJj6NUNJAKtGv" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Making Dreams Come True</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">Watch the magical moment when Lily received her wish for a puppy.</p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="A group of volunteers painting a mural on a wall" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5LSIuPNkxSzbcnvdeSWGjQSmhrY4K9TRkvNBXtLX0WN-MfxQ6J5SLru5wLWI5r3WzG7vMKjQmR4v6iVbX5f6Zv8-6lzQxfCTbS49zzaipSaFeDX4T0FU3AR52HCMpOlrrqGGUlh1QK-j4UfWil79gDn3MY8NB_Jzl9FHlq1L7UnTa0wKzdD53FMMxvvBcZh2vrE8xrWP0iGpFCk4x1C0d75mcdvrVOuPrveMeNN5x2gYacJdREVhHwzZsEVAG8OSAveaOoP8AaZ8N" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Our Community in Action</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">A behind-the-scenes look at our amazing volunteers making a difference.</p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="Vibrant pink and yellow flowers in a field" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHvxeA_4BoN3HwlHPMrknpFvMIos314xbgT4QlKVfigG_4H_mLqXAdliOdQUugqiXVVsnCEhD1v9xVHwe4SqQnwNZL0F7yLO--prg53-E9bnA3LufkV6MFnV9DhJclnYI0hqmRpPDqmiROSDU8gehURRFWDwGBuNJ2QPVQWTBaJ1Xsab7kbzoZKpLwCGazNMK3UxGlbaxOuTgbf65-nEIApxfNHz6WU3BJFzjtpVqcehc-sFFg1kIB9Lub8EFcXjUIjR6MOBcdrZVC" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hope in Full Bloom</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">A recap of our latest fundraising event that helps wishes blossom.</p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="White daisy flower with a blurred background" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXbTgJea969-XnvTxu4Jth559Ce0GPsvuvWdSVmj7hFWMux07-lE9UW1xB0ksCe0d1zRUaqKKhQdqhY6zI5NVxs2e_q3pbPNqFGwxWkwGlf9RWjS-YFOUQOOva3BsvIONfBTf3MjbOEeTvnHWmaSkS7w5VX6MLvsodPKpTr_MZiGhqXQvfOUmMIXaq7jCmG89ajI5qszKOCJQT__pU2fQSZgMGgaq-nxI6kNNIdXCHGRxukbagyvbIXIBQDufs09iRoYyaS9J_L2dq" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">A Simple Wish of Hope</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">Small acts of kindness can create the biggest ripples of happiness.</p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden group">
            <div class="relative">
              <img alt="Colorful abstract mandala pattern" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhdrVxIZoDfcsOXj7y05RPC7VVJK4wKJEkpWFsKL_TUZ-mWCxO4FYyUcLnLJOdxu8Z6y2LA5S0XWMFQv8OaTEczOJ9JPWuwmyuygM0KE-fLhARYvkUz08yhoHU44cFbMdrU0cXtBkyjllJQV1jIwvVYFtMxsiGtWsua3KLYZ3SvzUBIq-X12xfRlmpo1Z72hZ5ih6BAKAFrGsFr6lEq4kl3cK1eEnCM4fIRsdi3hbLKzXCa7SC3kVQseUUWocUnrkHr-roEIciZxNs" />
              <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
              </div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Creativity for a Cause</h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm flex-grow">An inspiring story of how art therapy is bringing joy to children.</p>
            </div>
          </div>
        </div>
        <nav class="flex justify-center mt-12">
          <ul class="flex items-center -space-x-px h-10 text-base">
            <li>
              <a class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-slate-500 bg-white border border-e-0 border-slate-300 rounded-s-lg hover:bg-slate-100 hover:text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white" href="#">
                <span class="material-icons text-lg">chevron_left</span>
              </a>
            </li>
            <li>
              <a aria-current="page" class="z-10 flex items-center justify-center px-4 h-10 leading-tight text-white border border-primary bg-primary hover:bg-primary/90" href="#">1</a>
            </li>
            <li>
              <a class="flex items-center justify-center px-4 h-10 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-100 hover:text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white" href="#">2</a>
            </li>
            <li>
              <a class="flex items-center justify-center px-4 h-10 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-100 hover:text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white" href="#">3</a>
            </li>
            <li>
              <a class="flex items-center justify-center px-4 h-10 leading-tight text-slate-500 bg-white border border-slate-300 rounded-e-lg hover:bg-slate-100 hover:text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white" href="#">
                <span class="material-icons text-lg">chevron_right</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </main>
@endsection
