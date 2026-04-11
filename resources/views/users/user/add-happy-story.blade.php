@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Tell Your Happy Story')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        <div class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-8">
          <div>
            <h1 class="text-3xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Tell Your Happy Story</h1>
            <p class="mt-2 text-text-muted-light dark:text-text-muted-dark">Share the story behind your wish and celebrate the moment.</p>
          </div>

          <form class="space-y-6">
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish">Wishes <span class="text-red-500">*</span></label>
              <select class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary"
                id="wish" name="wish">
                <option>--Wishes List--</option>
                <option>Flight to home</option>
                <option>Hospital ride</option>
                <option>Concert ticket</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="story-text">Story Text <span class="text-red-500">*</span></label>
              <textarea class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary"
                id="story-text" name="story-text" rows="4" placeholder="Share your story..."></textarea>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="story-image">Story Image</label>
              <input class="w-full text-sm file:mr-4 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-primary/20 file:text-brand-blue-light file:font-semibold file:cursor-pointer border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-surface-dark text-text-light dark:text-text-dark"
                id="story-image" name="story-image" type="file" accept="image/*" />
            </div>

            <div class="space-y-3">
              <p class="text-sm font-semibold text-text-light dark:text-text-dark">Or, choose one from the default images below</p>
              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                <!-- default image picks -->
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=400&q=80" alt="Default image 1" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=400&q=80" alt="Default image 2" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1415889455891-23bbf19ee5c7?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1415889455891-23bbf19ee5c7?auto=format&fit=crop&w=400&q=80" alt="Default image 3" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=400&q=80" alt="Default image 4" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80" alt="Default image 5" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80&sat=-60" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80&sat=-60" alt="Default image 6" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1450858933126-9cc56275fc6a?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1450858933126-9cc56275fc6a?auto=format&fit=crop&w=400&q=80" alt="Default image 7" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=400&q=80" alt="Default image 8" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1470137430626-983a37b8ea46?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1470137430626-983a37b8ea46?auto=format&fit=crop&w=400&q=80" alt="Default image 9" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1504198458649-3128b932f49b?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1504198458649-3128b932f49b?auto=format&fit=crop&w=400&q=80" alt="Default image 10" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=400&q=80" alt="Default image 11" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1504196606672-aef5c9cefc92?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1504196606672-aef5c9cefc92?auto=format&fit=crop&w=400&q=80" alt="Default image 12" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=400&q=80" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=400&q=80" alt="Default image 13" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=400&q=80&sat=-40" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=400&q=80&sat=-40" alt="Default image 14" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
                <label class="relative block cursor-pointer group">
                  <input class="peer sr-only" name="default-image" type="radio" value="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80&sat=20" />
                  <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                    src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=400&q=80&sat=20" alt="Default image 15" />
                  <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                </label>
              </div>
            </div>

            <div class="pt-2">
              <button class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-emerald-500 text-white font-semibold shadow hover:shadow-md transition"
                type="button">
                Create
              </button>
            </div>
          </form>
        </div>
      </section>
    </main>
@endsection
