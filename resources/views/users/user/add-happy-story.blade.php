@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@php
  $isEdit = isset($story);
  $selectedWishId = old('wish_id', $story->wish_id ?? '');
  $storyTextValue = old('story_text', $story->story_text ?? '');
  $currentImage = $story->story_image ?? null;
  $selectedDefaultImage = old(
    'story_image_default',
    $currentImage && !str_starts_with($currentImage, 'uploads/happy-stories/') ? $currentImage : null
  );
  $currentImageUrl = $currentImage ? (filter_var($currentImage, FILTER_VALIDATE_URL) ? $currentImage : '/' . ltrim($currentImage, '/')) : null;
@endphp

@section('title', $isEdit ? 'Edit Happy Story' : 'Tell Your Happy Story')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        <div class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-8">
          <div>
            <h1 class="text-3xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">{{ $isEdit ? 'Edit Happy Story' : 'Tell Your Happy Story' }}</h1>
            <p class="mt-2 text-text-muted-light dark:text-text-muted-dark">{{ $isEdit ? 'Update your story details and image.' : 'Share the story behind your wish and celebrate the moment.' }}</p>
          </div>

          @if(session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
              {{ session('status') }}
            </div>
          @endif

          @if($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/50 dark:text-red-200">
              <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="space-y-6" method="POST" action="{{ $isEdit ? route('happy.stories.update', $story->hs_id) : route('happy.stories.store') }}" enctype="multipart/form-data" id="happy-story-form">
            @csrf
            @if($isEdit)
              @method('PUT')
            @endif
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish">Wishes <span class="text-red-500">*</span></label>
              <select class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary"
                id="wish" name="wish_id">
                <option value="">--Wishes List--</option>
                @foreach($wishes ?? [] as $wish)
                  <option value="{{ $wish->w_id }}" @selected($selectedWishId == $wish->w_id)>
                    {{ $wish->wish_title ?: 'Untitled wish' }}
                  </option>
                @endforeach
              </select>
              <p class="text-sm text-red-600 hidden" data-error-for="wish_id">Please select a wish.</p>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="story-text">Story Text <span class="text-red-500">*</span></label>
              <textarea class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary"
                id="story-text" name="story_text" rows="4" placeholder="Share your story...">{{ $storyTextValue }}</textarea>
            </div>

            @if($isEdit && $currentImageUrl)
              <div class="space-y-3">
                <p class="block text-sm font-semibold text-text-light dark:text-text-dark">Current Image</p>
                <img src="{{ $currentImageUrl }}" alt="Current happy story image" class="h-40 w-40 rounded-xl object-cover border border-gray-200 dark:border-gray-700" />
                <label class="inline-flex items-center gap-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                  <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(old('remove_image')) />
                  Remove current image
                </label>
              </div>
            @endif

            <div class="space-y-2">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="story-image">Story Image @if(!$isEdit || !$currentImageUrl)<span class="text-red-500">*</span>@endif</label>
              <input class="w-full text-sm file:mr-4 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-primary/20 file:text-brand-blue-light file:font-semibold file:cursor-pointer border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-surface-dark text-text-light dark:text-text-dark"
                id="story-image" name="story_image_upload" type="file" accept="image/*" />
              <p class="text-sm text-red-600 hidden" data-error-for="story_image_choice">Please upload an image or choose one from the list.</p>
            </div>

            @php
              $defaultStoryImages = [];
              $candidateStoryImageDirectories = [
                  public_path('images/happy-stories-default'),
                  base_path('../public_html/images/happy-stories-default'),
              ];

              foreach ($candidateStoryImageDirectories as $defaultStoryDir) {
                  if (!is_dir($defaultStoryDir)) {
                      continue;
                  }

                  foreach (\Illuminate\Support\Facades\File::files($defaultStoryDir) as $file) {
                      $ext = strtolower($file->getExtension());
                      if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                          $defaultStoryImages[] = 'images/happy-stories-default/' . $file->getFilename();
                      }
                  }

                  if ($defaultStoryImages !== []) {
                      break;
                  }
              }
            @endphp
            <div class="space-y-3">
              <p class="text-sm font-semibold text-text-light dark:text-text-dark">Or, choose one from the default images below</p>
              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                @forelse ($defaultStoryImages as $index => $imagePath)
                  <label class="relative block cursor-pointer group">
                    <input class="peer sr-only" name="story_image_default" type="radio" value="{{ $imagePath }}" @checked($selectedDefaultImage === $imagePath) />
                    <img class="h-20 w-full object-cover rounded-lg border border-transparent peer-checked:border-2 peer-checked:border-primary shadow-sm"
                      src="/{{ ltrim($imagePath, '/') }}" alt="Default image {{ $index + 1 }}" />
                    <span class="absolute inset-0 rounded-lg ring-2 ring-transparent peer-checked:ring-primary/70"></span>
                  </label>
                @empty
                  <div class="col-span-full text-sm text-text-muted-light dark:text-text-muted-dark px-2 py-3">
                    No default images found in the happy stories default image directory.
                  </div>
                @endforelse
              </div>
            </div>

            <div class="pt-2">
                <button class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-emerald-500 text-white font-semibold shadow hover:shadow-md transition"
                type="submit">
                {{ $isEdit ? 'Save Changes' : 'Create' }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </main>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('happy-story-form');
    if (!form) return;

    const wishSelect = form.querySelector('[name="wish_id"]');
    const imageInput = form.querySelector('[name="story_image_upload"]');
    const errorWish = form.querySelector('[data-error-for="wish_id"]');
    const errorImage = form.querySelector('[data-error-for="story_image_choice"]');

    form.addEventListener('submit', function (event) {
      let valid = true;
      const selectedDefault = form.querySelector('[name="story_image_default"]:checked');
      const hasUpload = !!(imageInput && imageInput.files && imageInput.files.length > 0);
      const hasDefault = !!selectedDefault;
      const removeImage = !!form.querySelector('[name="remove_image"]:checked');
      const hasCurrentImage = {!! json_encode((bool) $currentImageUrl) !!};

      if (!wishSelect || !wishSelect.value) {
        valid = false;
        if (errorWish) errorWish.classList.remove('hidden');
      } else if (errorWish) {
        errorWish.classList.add('hidden');
      }

      if (hasUpload && hasDefault) {
        valid = false;
        if (errorImage) errorImage.classList.remove('hidden');
      } else if (!hasUpload && !hasDefault && (!hasCurrentImage || removeImage)) {
        valid = false;
        if (errorImage) errorImage.classList.remove('hidden');
      } else if (errorImage) {
        errorImage.classList.add('hidden');
      }

      if (!valid) {
        event.preventDefault();
      }
    });
  });
</script>
@endsection
