@extends('layouts.app')

@section('title', 'Simply Wishes - Granting Wishes &amp; Making Dreams Come True')

@section('content')
<main class="flex-grow">
      <section class="relative overflow-hidden py-20 md:py-32 bg-surface-light dark:bg-surface-dark">
        <div class="absolute inset-0 opacity-10 dark:opacity-20">
          <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-primary/30 rounded-full filter blur-3xl"></div>
          <div
            class="absolute bottom-[-50px] right-[-50px] w-72 h-72 bg-brand-blue-light/20 dark:bg-brand-blue-dark/20 rounded-full filter blur-3xl">
          </div>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div class="text-center">
            <h1
              class="font-display text-4xl sm:text-5xl md:text-7xl font-bold text-brand-blue-light dark:text-white leading-tight">
              A single wish can <span class="text-primary">spark a universe</span> of joy.
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg text-text-muted-light dark:text-text-muted-dark">
              Join us in creating life-changing wish experiences for children with critical illnesses. Your support
              brings hope, strength, and happiness to them and their families.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 hidden">
              <a class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold bg-primary text-brand-blue-light rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-300"
                href="#">
                <span class="material-symbols-outlined">auto_awesome</span>
                Grant a Wish
              </a>
              <a class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold bg-surface-light dark:bg-surface-dark text-brand-blue-light dark:text-brand-blue-dark border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-lg hover:scale-105 transform transition-all duration-300"
                href="#">
                <span class="material-symbols-outlined">edit</span>
                Make a Wish
              </a>
            </div>
          </div>
        </div>
      </section>
      <section class="py-20 md:py-28 bg-background-light dark:bg-background-dark">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold font-display text-brand-blue-light dark:text-white">Current Wishes
            </h2>
            <p class="mt-4 max-w-xl mx-auto text-text-muted-light dark:text-text-muted-dark">
              These are the dreams waiting to come true. A small act of kindness can make a world of difference.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="An elephant being lifted by a colorful hot air balloon towards the moon"
                  class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMr6ubBKvRNd3FOwHuMykLiH_j8roKp6Ytn2m5UcdsPG8DXxv4ejRYHC6-W64HFlw3b6agdPWCMvBDEdK5iK53w4kpM05TCbEjgCnov22yvQaduWOtTYIvyZOhCYddC8D1Zaev12e__zBtjQdhxXy6Q79kXkJNbNsk2Xgj5X1qxihnnIcD6yvh2aVqE62Ztzrw7NjhVHlpll6WiWkrY9Cra-CDcoU0bvMPwIAxF1vSAB7nUrVunwWX_ZSFpM1OiBB5hdR3cDmBQWou" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to fly to the moon</h3>
                  <p class="text-sm text-gray-200">Sarah, Age 7</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Sarah dreams of
                  being an astronaut and wants to see the earth from the moon. She loves space and stars more than
                  anything.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$4,500 raised</span>
                  <span>$10,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A close-up of a white daisy flower with a yellow center" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6wVmBKgrBH0a4rKpY3r-3t4xv4-JmdpL2bhPB4DpUoBgDyRMfO21FWwN0CdGTj5_mAVtKrbgO8aE-SF-mf1uikYxyBmTJpXXqi2QVKG-I6IF_zqi55fQwnohyZYbdFDFiZnFGj_fEZfQJ_Qw78EuSkmhyXFYSJBswZjBe02ak37IJDC5TLS6A9DA8p9T_rbqrR-lN9-_HUfkSJVLT1I4owxzXwinpfE5Os3cRxsp9NQuVUvTRJf6_sk4zPtZGSTGgvxm0IUnIICI8" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to have a flower garden</h3>
                  <p class="text-sm text-gray-200">Lily, Age 9</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Lily finds joy in
                  nature and wants to create a beautiful garden where she can watch butterflies and bees play among the
                  flowers.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 78%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$1,560 raised</span>
                  <span>$2,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A fluffy dandelion seed head ready to be blown" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuApVo_SPZGdAcRruTHupkQXrKqysvKYXTjsgnGiRc54F1zGn1w2w1vHrEBPC2eM3O1AxgVlZeUEW7tnyXw7hdabWyhQGqwe7uQ1x2oUG6Uhf7HW3Vfw4-phABipdhA8sCJUK673aA2gGKOE-K6kHm31dpMPE428qLQ60Uf0Runu643Lplf1_VpoH4Xj7GGnpZsGCMOY8O54WUPqaB2stQmfmOKpKCBFi_UT0bXC2Iwp7ULz6U194n0tFncpDVO5H0Wbw9lvenDUBlSq" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish for a new puppy</h3>
                  <p class="text-sm text-gray-200">Leo, Age 6</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Leo wants a furry
                  best friend to cuddle with and play fetch. A puppy would bring him immense comfort and companionship.
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 95%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$1,425 raised</span>
                  <span>$1,500 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A child dressed as a pirate looking through a telescope" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuApVo_SPZGdAcRruTHupkQXrKqysvKYXTjsgnGiRc54F1zGn1w2w1vHrEBPC2eM3O1AxgVlZeUEW7tnyXw7hdabWyhQGqwe7uQ1x2oUG6Uhf7HW3Vfw4-phABipdhA8sCJUK673aA2gGKOE-K6kHm31dpMPE428qLQ60Uf0Runu643Lplf1_VpoH4Xj7GGnpZsGCMOY8O54WUPqaB2stQmfmOKpKCBFi_UT0bXC2Iwp7ULz6U194n0tFncpDVO5H0Wbw9lvenDUBlSq" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to be a pirate</h3>
                  <p class="text-sm text-gray-200">Max, Age 8</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Max dreams of
                  sailing the seven seas on a pirate ship, searching for buried treasure and having grand adventures on
                  the open ocean.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 60%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$3,000 raised</span>
                  <span>$5,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A child painting a colorful masterpiece on a canvas" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6wVmBKgrBH0a4rKpY3r-3t4xv4-JmdpL2bhPB4DpUoBgDyRMfO21FWwN0CdGTj5_mAVtKrbgO8aE-SF-mf1uikYxyBmTJpXXqi2QVKG-I6IF_zqi55fQwnohyZYbdFDFiZnFGj_fEZfQJ_Qw78EuSkmhyXFYSJBswZjBe02ak37IJDC5TLS6A9DA8p9T_rbqrR-lN9-_HUfkSJVLT1I4owxzXwinpfE5Os3cRxsp9NQuVUvTRJf6_sk4zPtZGSTGgvxm0IUnIICI8" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish for art supplies</h3>
                  <p class="text-sm text-gray-200">Chloe, Age 11</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Chloe is a talented
                  artist who wishes for a complete set of professional art supplies to create her paintings and
                  drawings.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 85%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$425 raised</span>
                  <span>$500 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A beautiful horse standing in a green field" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMr6ubBKvRNd3FOwHuMykLiH_j8roKp6Ytn2m5UcdsPG8DXxv4ejRYHC6-W64HFlw3b6agdPWCMvBDEdK5iK53w4kpM05TCbEjgCnov22yvQaduWOtTYIvyZOhCYddC8D1Zaev12e__zBtjQdhxXy6Q79kXkJNbNsk2Xgj5X1qxihnnIcD6yvh2aVqE62Ztzrw7NjhVHlpll6WiWkrY9Cra-CDcoU0bvMPwIAxF1vSAB7nUrVunwWX_ZSFpM1OiBB5hdR3cDmBQWou" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to ride a horse</h3>
                  <p class="text-sm text-gray-200">Mia, Age 10</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Mia has always been
                  fascinated by horses and dreams of spending a day at a ranch, learning to ride and care for these
                  majestic animals.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 30%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$600 raised</span>
                  <span>$2,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A young girl in a princess dress in a castle" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6wVmBKgrBH0a4rKpY3r-3t4xv4-JmdpL2bhPB4DpUoBgDyRMfO21FWwN0CdGTj5_mAVtKrbgO8aE-SF-mf1uikYxyBmTJpXXqi2QVKG-I6IF_zqi55fQwnohyZYbdFDFiZnFGj_fEZfQJ_Qw78EuSkmhyXFYSJBswZjBe02ak37IJDC5TLS6A9DA8p9T_rbqrR-lN9-_HUfkSJVLT1I4owxzXwinpfE5Os3cRxsp9NQuVUvTRJf6_sk4zPtZGSTGgvxm0IUnIICI8" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to be a princess</h3>
                  <p class="text-sm text-gray-200">Olivia, Age 5</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Olivia dreams of a
                  magical day where she gets to wear a beautiful gown, ride in a carriage, and be a princess for a day.
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 90%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$2,700 raised</span>
                  <span>$3,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A chef's hat and a wooden spoon on a kitchen counter" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMr6ubBKvRNd3FOwHuMykLiH_j8roKp6Ytn2m5UcdsPG8DXxv4ejRYHC6-W64HFlw3b6agdPWCMvBDEdK5iK53w4kpM05TCbEjgCnov22yvQaduWOtTYIvyZOhCYddC8D1Zaev12e__zBtjQdhxXy6Q79kXkJNbNsk2Xgj5X1qxihnnIcD6yvh2aVqE62Ztzrw7NjhVHlpll6WiWkrY9Cra-CDcoU0bvMPwIAxF1vSAB7nUrVunwWX_ZSFpM1OiBB5hdR3cDmBQWou" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish to be a chef</h3>
                  <p class="text-sm text-gray-200">Daniel, Age 12</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">Daniel loves
                  cooking and wants to spend a day with a professional chef, learning new recipes and cooking techniques
                  in a real restaurant kitchen.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 55%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$1,100 raised</span>
                  <span>$2,000 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Help Grant This Wish
                </a>
              </div>
            </div>
            <div
              class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 group">
              <div class="relative">
                <img alt="A stack of books with a magical glow" class="w-full h-60 object-cover"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuApVo_SPZGdAcRruTHupkQXrKqysvKYXTjsgnGiRc54F1zGn1w2w1vHrEBPC2eM3O1AxgVlZeUEW7tnyXw7hdabWyhQGqwe7uQ1x2oUG6Uhf7HW3Vfw4-phABipdhA8sCJUK673aA2gGKOE-K6kHm31dpMPE428qLQ60Uf0Runu643Lplf1_VpoH4Xj7GGnpZsGCMOY8O54WUPqaB2stQmfmOKpKCBFi_UT0bXC2Iwp7ULz6U194n0tFncpDVO5H0Wbw9lvenDUBlSq" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h3 class="text-2xl font-bold text-white font-display">I wish for books</h3>
                  <p class="text-sm text-gray-200">Sophia, Age 9</p>
                </div>
              </div>
              <div class="p-6">
                <p class="text-text-muted-light dark:text-text-muted-dark mb-4 h-20 overflow-hidden">An avid reader,
                  Sophia wishes for a personal library filled with her favorite book series and stories of adventure and
                  fantasy.</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                  <div class="bg-primary h-2.5 rounded-full" style="width: 100%"></div>
                </div>
                <div class="flex justify-between text-sm text-text-muted-light dark:text-text-muted-dark mb-6">
                  <span>$750 raised</span>
                  <span>$750 goal</span>
                </div>
                <a class="w-full block text-center px-6 py-3 font-semibold bg-brand-blue-light text-white dark:bg-brand-blue-dark dark:text-brand-blue-light rounded-lg group-hover:bg-primary group-hover:text-brand-blue-light transition-colors duration-300"
                  href="#">
                  Wish Granted!
                </a>
              </div>
            </div>
          </div>
          <div class="mt-12 text-center">
            <a class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-primary border-2 border-primary rounded-lg hover:bg-primary hover:text-brand-blue-light transition-colors"
              href="#">
              See All Wishes
              <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
        </div>
      </section>
      <section class="py-20 md:py-28 bg-surface-light dark:bg-surface-dark">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold font-display text-brand-blue-light dark:text-white">Watch Wishes
              Come True</h2>
            <p class="mt-4 max-w-xl mx-auto text-text-muted-light dark:text-text-muted-dark">
              See the incredible impact of your generosity through the eyes of our wish kids.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="relative rounded-xl overflow-hidden group shadow-lg">
              <img alt="Animated character on a mountain top"
                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBSIirWMHUTwT9IbS40Rn0nIhxA4yd_gWq2TUM7s8eKRl7QqUZHhLkG73L_UBwcwJP_kxWDbgJdu75ZUmVkACde7APapbn5QXLdTvphcaPvxKishkwiwX8W22xtIsVeNpy2re5Isd13LjL1H4QaPnhjNWNRSMc6EID0WfT9CA3eY7sw-rZBTYVFcbBI5UE8kKhkP9-r1uNMSYG--6LpsZgPqIZbIutsuST2DDD9kKH2R1YaYrkbeYUv3C2r2JZA-0oGZcgNNhZwmeT0" />
              <div class="absolute inset-0 bg-black/40"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <button
                  class="w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover:bg-white/50 transition-colors">
                  <span class="material-symbols-outlined text-5xl">play_arrow</span>
                </button>
              </div>
            </div>
            <div class="relative rounded-xl overflow-hidden group shadow-lg">
              <img alt="Animated character on a stage"
                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDdS_8Y2eouoGf1nocqT9O42WJFm9-deO9-n5d_oZ9-hjYsJZOu2CxpUU2xTZ-BXkR3DREgjwwrkGlxJtryHPibxVUN5XbRlHP2wb2Gki48u1DRjXqQs_6NOSpJrsMXEPeo5rK-Uwajn2LC478ELsHi9AjfWjJX0N3aoUsAs8BBpoHy5EXQesODbPlqo-XMVD26bWcYoh8Sazip5jdgSTi50cJc3baFf_S_rdDw_Je6kfsc11BbSK9_wv5tHqbtbvNmVKaD8s9kl4se" />
              <div class="absolute inset-0 bg-black/40"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <button
                  class="w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover:bg-white/50 transition-colors">
                  <span class="material-symbols-outlined text-5xl">play_arrow</span>
                </button>
              </div>
            </div>
            <div class="relative rounded-xl overflow-hidden group shadow-lg">
              <img alt="Animated thought bubble with question marks"
                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCz_4O1Nyp3PrwLYR4I0SowOSu7cW0HaZ4rkg0Rxh45OGR4aTTRPtwmiVyKlUmiVoOyZETxLpGOIhEJi8088pTv_qqZCaE3ilp4pQfmlG9zn85QCRdb6UvxuGTiCDCB2SsymRaORhlcjufl7a4yWuQRzCHWXQ594QRHd2_Q30SKPT4n4sV7c55-eAmK5XCXApjzPULCiDKIOAOZwzL8FUM0gtgZc311BdjA42kNKPxvXFY73pJqrkFDQJeHp2BhytZEggi5cIasfSia" />
              <div class="absolute inset-0 bg-black/40"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <button
                  class="w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover:bg-white/50 transition-colors">
                  <span class="material-symbols-outlined text-5xl">play_arrow</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection
