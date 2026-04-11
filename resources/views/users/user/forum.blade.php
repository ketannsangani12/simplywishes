@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes Forum')

@section('content')
<main class="flex-grow">
<div class="container mx-auto px-4 py-8 md:py-12">
<div class="text-center mb-8 md:mb-12">
<h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-2">Community Forum</h1>
<p class="text-lg text-slate-600 dark:text-slate-400">Share stories, ask questions, and connect with our community.</p>
</div>
<div class="mb-8 md:mb-10">
<div class="relative max-w-2xl mx-auto">
<input class="w-full pl-12 pr-4 py-3 rounded-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary focus:border-primary transition" placeholder="Search articles, videos, and more..." type="search"/>
<span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">search</span>
</div>
</div>
<div class="flex justify-center border-b border-slate-200 dark:border-slate-700 mb-8 md:mb-10">
<div class="flex space-x-4 sm:space-x-8">
<a class="py-3 px-2 text-primary border-b-2 border-primary font-semibold text-sm sm:text-base" href="forum.html">
<span class="material-icons align-middle mr-1 text-lg">article</span>
            Articles
          </a>
<a class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base" href="forum-video.html">
<span class="material-icons align-middle mr-1 text-lg">videocam</span>
            Videos
          </a>
<a class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base" href="forum-contribute.html">
<span class="material-icons align-middle mr-1 text-lg">volunteer_activism</span>
            Contribute
          </a>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="White daisy flower with a blurred background" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXbTgJea969-XnvTxu4Jth559Ce0GPsvuvWdSVmj7hFWMux07-lE9UW1xB0ksCe0d1zRUaqKKhQdqhY6zI5NVxs2e_q3pbPNqFGwxWkwGlf9RWjS-YFOUQOOva3BsvIONfBTf3MjbOEeTvnHWmaSkS7w5VX6MLvsodPKpTr_MZiGhqXQvfOUmMIXaq7jCmG89ajI5qszKOCJQT__pU2fQSZgMGgaq-nxI6kNNIdXCHGRxukbagyvbIXIBQDufs09iRoYyaS9J_L2dq"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">A Simple Wish of Hope</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Brews Hamilton" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8QK7f10g13q5wG-0qVJvCLR9xAOmNDJen7CAuNabqxwM3sKGM4DGsSrYScFfruWGdvs8RPXjZDMlniViO27f0siYY5ADevVkr1WmkK7a1NRMQPayi4KyleMgf2aOOzdA0Et5IjMKoAW-2D5ytGjUvkJR5pYqXfkt8UZasNcL1sUTkttbv9m3KP5Z-QVSmTJpk2aiPQW5HF2wRQvn-PULLil4-q935u6SjdcmP4X5PbdFIGDwTFpQDl47XWeOOib1IRoI9RJfvw3pl"/>
<span>Brews Hamilton</span>
<span class="mx-2">•</span>
<span>06-25-2025</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">Sometimes, the simplest things bring the most joy. A look into how small acts of kindness can create ripples of happiness.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="#">Read More →</a>
</div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="A beautiful waterfall in a lush green landscape" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCadECJkb5EEjovfwA0pmiVstQG3ovBtWQXSoykxOOK1wZVSN9thVtM9j-q9k2kX3Y9KlliB5qYR_hdrIxdYo6pd3VNIceAPEW43kqeJ79QmxLZwYYdvulP2l9wRGnQfdXzS48Refd3w9HMiqpHLE7nDbOyXcrkBXozfQgt7ShaulWhflJuDWbInOL-mdfJFklM82t3ewHSeB5w6i_TUAemzbhqC1OQ7DyD1Fs7-CF_QPxKE5hr6mUx80TT5zRLzfmFJj6NUNJAKtGv"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">Waterfall Wishes: A Story of Renewal</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Brews Hamilton" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDis7_c3d20Zt7a7-jjUs9pz0NK9U5HMNx78McliJZa0htN6OYYOUipttvFusMgMermkR-habNL9BOsz5kC6E5VD6wqAtwVc4fW4coLaNjSJY6kNfx9R0bllrht6xubq9mogBfTarj0DZ7Rokak7hS1vm6mBVlBnZI31Ge07qaP9bzF4RF55m2tUEMDxNlyOm9RVZrI2eBiNFzm1_gUXK2U-4F3QZP53khAd33An6EDMaRVgrNV6W9_uWCinwCzrtbkInBdL3cO0i80"/>
<span>Brews Hamilton</span>
<span class="mx-2">•</span>
<span>06-25-2025</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">Exploring the therapeutic power of nature and how a trip to the falls helped one family heal and find hope again.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="#">Read More →</a>
</div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="Vibrant pink and yellow flowers in a field" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHvxeA_4BoN3HwlHPMrknpFvMIos314xbgT4QlKVfigG_4H_mLqXAdliOdQUugqiXVVsnCEhD1v9xVHwe4SqQnwNZL0F7yLO--prg53-E9bnA3LufkV6MFnV9DhJclnYI0hqmRpPDqmiROSDU8gehURRFWDwGBuNJ2QPVQWTBaJ1Xsab7kbzoZKpLwCGazNMK3UxGlbaxOuTgbf65-nEIApxfNHz6WU3BJFzjtpVqcehc-sFFg1kIB9Lub8EFcXjUIjR6MOBcdrZVC"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">Our Community in Full Bloom</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Brews Hamilton" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAnzw_IQkRpIFYk5h4seDeipOx-MqwRXwtbiPNcr7FVrcTorhNjZmw8il72ntHcrLDRLwftDr5tqLUbMEt_vye06ji2l3bsJx6SrhqqJCAIF_pa_ZQ75tY3r5qtHYtyftyKSOCES81CXhXd9fD43s_MMTXsY0YRMlkf9uVy4pWhe-DhgjEZv1x9j70RrSnl8TWGCSFItKuiUHFZK0DpYch7sYHIIyAuk8cfZntYRuWHuAjjinEeRF9wPVJ59yJ8KscBqnhgS_rCMy2q"/>
<span>Brews Hamilton</span>
<span class="mx-2">•</span>
<span>06-25-2025</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">A recap of our latest fundraising event and the incredible generosity that helps wishes blossom for children in need.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="#">Read More →</a>
</div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="Tree in a field during a beautiful sunset" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgjLr9Yy-8UC_ItkFDDpG8IFy9AO5itQKzdZt_3XiPrGQaY05gfSf_5HB2bVvnr1bUTYL7xl7tM3D_IbK6KPJf2TIHIQme-G-hYl0J1ss8nj-AearirxImtTYsKFQcq0q6Qs23XIToJ-oCNisZIiy23vPxVJViHnXnov7ydeytj1De0AESsRV8iHXwUFB_1ppVeCSzK3As42pXvEXAMfw_T9maO_8YHbmb3QaX8qKIWK4a-NoSDkChD7JwhliH3J3jmohRzaO29sDf"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">Video: Sunset of Gratitude</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Brews Hamilton" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB7H6h-2x7Z3lTFzY7f0j5ohCo4oDwyLHK1C65eVNGYyBd34Ous3Rrh1rri5Yg-Oltv1q2RfLB-6QuVtFlNSc4BTHDs4eBH5EgRjFBT-gG6PL11uZoI7LOQa8RHsyMdwBpkXwly4TG4IgzWPp3_bFSN5H00To5iO2oXEHlRTrqEg6WEuFsbD1skJM_6L4ilsfXbChHTDvwT8dkZGvpgvMImyU_1Ty_D31otjQpVk68rYYlmQvnP-ddNGn0dosi21uxFQ6iPW_Zke5Yw"/>
<span>Brews Hamilton</span>
<span class="mx-2">•</span>
<span>05-30-2025</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">Watch our latest video featuring heartfelt thank you messages from wish recipients and their families.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="forum-video.html">Watch Now →</a>
</div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="People sitting around a table in a meeting" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5LSIuPNkxSzbcnvdeSWGjQSmhrY4K9TRkvNBXtLX0WN-MfxQ6J5SLru5wLWI5r3WzG7vMKjQmR4v6iVbX5f6Zv8-6lzQxfCTbS49zzaipSaFeDX4T0FU3AR52HCMpOlrrqGGUlh1QK-j4UfWil79gDn3MY8NB_Jzl9FHlq1L7UnTa0wKzdD53FMMxvvBcZh2vrE8xrWP0iGpFCk4x1C0d75mcdvrVOuPrveMeNN5x2gYacJdREVhHwzZsEVAG8OSAveaOoP8AaZ8N"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">Join Our Volunteer Team!</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Jane Doe" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCN0zKViZr3kg2Dwra6MovAk4bAzz1rxaEWuvKTFfI4VD0MgGea2i7uAWZEmgGUxDNWLFPMDQP14qIlKWLpgDVuR4fcse43Rf7dcIwWbAB-7bIFKOVuRukU4qd2thvt3lglcHKQPNZPSgi6I__d9NaM9nLMjir7yYJlTVB6_l4yaOCIR7PjS_GbrSfC41G-kae5CGOIEpV8W8Q1ELRMuj73MWp7xAGPrgdKbwzzh83C4MUZS1XeWFTgNfOlJ-849SkZKtfaIK5z3_CG"/>
<span>Jane Doe</span>
<span class="mx-2">•</span>
<span>07-15-2024</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">We're looking for passionate individuals to help us grant more wishes. Learn how you can make a difference.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="forum-contribute.html">Learn More →</a>
</div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
<img alt="Colorful abstract mandala pattern" class="w-full h-48 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhdrVxIZoDfcsOXj7y05RPC7VVJK4wKJEkpWFsKL_TUZ-mWCxO4FYyUcLnLJOdxu8Z6y2LA5S0XWMFQv8OaTEczOJ9JPWuwmyuygM0KE-fLhARYvkUz08yhoHU44cFbMdrU0cXtBkyjllJQV1jIwvVYFtMxsiGtWsua3KLYZ3SvzUBIq-X12xfRlmpo1Z72hZ5ih6BAKAFrGsFr6lEq4kl3cK1eEnCM4fIRsdi3hbLKzXCa7SC3kVQseUUWocUnrkHr-roEIciZxNs"/>
<div class="p-6 flex flex-col flex-grow">
<h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">Peace Now: A Call for Unity</h2>
<div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
<img alt="Avatar of Arisa Tele" class="w-6 h-6 rounded-full mr-2 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDlhJtN5JF1Pq1tovaCuXQy-q5glcB2eKDrErr8AgoZUjo4wmhM1xXvl0QLL3h3otzhYdWaHnfr0ZKg21cSScl0kbwk_LQP2WvuuEN-Z1y1LjaoXr8TVk2vpEv4IImcCEDKYPctiRV9SaC7vvVOCRHwSmut0eTgZEm14AAseQmj0hRzkyE0L6QVfEQZU-pFouw0BgTtBwYKVQyWMIX_BbtBlEuvzbtaWIi04BBn4nzYl00KxNN0Lua9C43gRS0k07KqPf1PwTyY3pCd"/>
<span>Arisa Tele</span>
<span class="mx-2">•</span>
<span>07-11-2024</span>
</div>
<p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">Why are humans so violent? We need peace now! Join the conversation on how we can foster a more compassionate world.</p>
<a class="text-primary font-semibold hover:underline mt-auto self-start" href="#">Read More →</a>
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
