@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes Forum')

@php
  $tabLabels = [
    'articles' => 'Articles',
    'videos' => 'Videos',
    'contribute' => 'Contribute',
  ];

  $forumImage = function ($post) {
    $image = $post->imageUrl();

    if (! $image) {
      return $post->is_video_only
        ? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80'
        : 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=1200&q=80';
    }

    return $image;
  };

  $authorName = function ($user) {
    if (! $user) {
      return 'Simply Wishes';
    }

    $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));

    return $name !== '' ? $name : ($user->name ?: 'Simply Wishes');
  };
@endphp

@section('content')
<main class="flex-grow bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-8 sm:mb-12">
        <p class="text-sm uppercase tracking-[0.24em] text-text-muted-light dark:text-text-muted-dark">Community Forum</p>
        <h1 class="mt-2 text-4xl sm:text-5xl font-bold text-brand-blue-light dark:text-white">Stories, videos, and voices from our community</h1>
        <p class="mt-3 text-lg text-text-muted-light dark:text-text-muted-dark">
          Browse real posts shared by the Simply Wishes community.
        </p>
      </div>

      <form method="GET" action="{{ route('forum') }}" class="mb-8">
        <input type="hidden" name="tab" value="{{ $tab }}" />
        <div class="relative max-w-2xl mx-auto">
          <input
            name="q"
            value="{{ $searchTerm }}"
            class="w-full pl-12 pr-4 py-3 rounded-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary focus:border-primary transition"
            placeholder="Search posts by title or content"
            type="search"
          />
          <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">search</span>
        </div>
      </form>

      <div class="flex justify-center border-b border-slate-200 dark:border-slate-700 mb-8 sm:mb-10">
        <div class="flex space-x-4 sm:space-x-8">
          @foreach ($tabLabels as $key => $label)
            <a
              class="py-3 px-2 {{ $tab === $key ? 'text-primary border-b-2 border-primary font-semibold' : 'text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium' }} text-sm sm:text-base"
              href="{{ route('forum', ['tab' => $key, 'q' => $searchTerm ?: null]) }}"
            >
              {{ $label }}
            </a>
          @endforeach
        </div>
      </div>

      @if (session('status'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-900 dark:bg-red-950/50 dark:text-red-200">
          <p class="font-semibold">Please fix the following errors:</p>
          <ul class="mt-2 list-disc pl-5 space-y-1 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($tab === 'contribute')
        <div class="mb-10" data-contribute-tabs data-initial-tab="{{ old('post_type', 'article') }}">
          <div class="max-w-4xl mx-auto">
            <div class="flex justify-center mb-8">
              <div class="flex rounded-full p-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-x-1">
                <button class="px-6 py-2 rounded-full border font-semibold transition-all duration-300 bg-brand-blue-light text-white shadow-md border-transparent" type="button" data-contribute-tab-button="article">
                  Contribute Article
                </button>
                <button class="px-6 py-2 rounded-full border font-semibold transition-all duration-300 bg-transparent text-slate-500 dark:text-slate-300 border-transparent hover:bg-white/70 dark:hover:bg-slate-700/70" type="button" data-contribute-tab-button="video">
                  Contribute Video
                </button>
              </div>
            </div>

            <div data-contribute-tab-panel="article">
              <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Article</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-6">We invite you to post an article about a topic of general interest, and let your voice be heard.</p>
                <form class="space-y-6" method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
                  @csrf
                  <input name="post_type" type="hidden" value="article" />
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-title">Title <span class="text-red-500">*</span></label>
                    <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-title" name="article_title" value="{{ old('article_title') }}" type="text" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-content">Write or Insert your article in the space provided below <span class="text-red-500">*</span></label>
                    <div class="rounded-md border border-slate-300 dark:border-slate-600">
                      <div class="flex items-center p-2 border-b border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 space-x-1 text-slate-600 dark:text-slate-300">
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">undo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">redo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_bold</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_italic</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_underlined</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">strikethrough_s</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">link</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_quote</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">image</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">help_outline</span></button>
                      </div>
                      <textarea class="w-full h-48 p-3 bg-transparent border-0 focus:ring-0 resize-y dark:text-white" id="article-content" name="article_content">{{ old('article_content') }}</textarea>
                    </div>
                  </div>
                  <div class="space-y-5">
                    <div>
                      <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-1">Upload a Video for your Article <span class="text-slate-500 dark:text-slate-400 font-normal">(Optional)</span></h3>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                      <div class="space-y-4">
                        <div>
                          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-url">Insert youtube video url in order to upload</label>
                          <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-video-url" name="article_featured_video_url" value="{{ old('article_featured_video_url') }}" placeholder="http:// or https://" type="url" />
                        </div>
                        <div>
                          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-file">Or, Upload from your Files</label>
                          <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Choose a video that you own. Do not upload any copyright images such as images from movie characters or known company products or your post could be deleted without warning.</p>
                          <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="article-video-file" name="article_featured_video_file" type="file" />
                        </div>
                      </div>
                      <div class="space-y-4">
                        <div>
                          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-thumbnail">Upload a Thumbnail Image for your Video related to the Article</label>
                          <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="article-video-thumbnail" name="article_thumbnail" type="file" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-end">
                    <button class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Create</button>
                  </div>
                </form>
              </div>
            </div>

            <div class="hidden" data-contribute-tab-panel="video">
              <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Video</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-6">We invite you to post a video, about a topic of general interest, and let your voice be heard.</p>
                <form class="space-y-6" method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
                  @csrf
                  <input name="post_type" type="hidden" value="video" />
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-title">Title <span class="text-red-500">*</span></label>
                    <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="video-title" name="video_title" value="{{ old('video_title') }}" type="text" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-description">Description</label>
                    <div class="rounded-md border border-slate-300 dark:border-slate-600">
                      <div class="flex items-center p-2 border-b border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 space-x-1 text-slate-600 dark:text-slate-300">
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">undo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">redo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_bold</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_italic</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_underlined</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">strikethrough_s</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">link</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_quote</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">image</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">help_outline</span></button>
                      </div>
                      <textarea class="w-full h-32 p-3 bg-transparent border-0 focus:ring-0 resize-y dark:text-white" id="video-description" name="video_content">{{ old('video_content') }}</textarea>
                    </div>
                  </div>
                  <div>
                    <p class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Upload a Video <span class="text-red-500">*</span></p>
                    <div class="space-y-4">
                      <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="youtube-url">Insert youtube video url in order to upload</label>
                        <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="youtube-url" name="video_featured_video_url" value="{{ old('video_featured_video_url') }}" placeholder="http:// or https://" type="url" />
                      </div>
                      <div class="text-center text-slate-500 dark:text-slate-400">( Or )</div>
                      <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="video-file">Upload from your Files</label>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mb-2">Choose a video that you own. Do not upload any copyright images such as images from movie characters or known company products or your post could be deleted without warning.</p>
                        <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="video-file" name="video_featured_video_file" type="file" />
                      </div>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="thumbnail-image">Upload a Thumbnail Image for your Video <span class="text-red-500">*</span></label>
                    <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="thumbnail-image" name="video_thumbnail" type="file" />
                  </div>
                  <div class="flex justify-end">
                    <button class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Create</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      @endif

      @if ($tab !== 'contribute' && $posts->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-6 py-16 text-center">
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">No posts found</h2>
          <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try another search term or switch tabs.</p>
        </div>
      @elseif ($tab !== 'contribute')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach ($posts as $post)
            @php
              $image = $forumImage($post);
              $creator = $post->creator;
              $creatorLabel = $authorName($creator);
            @endphp

            <article class="bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
              <div class="relative">
                <a href="{{ route('forum.show', $post->e_id) }}" class="block group" aria-label="Open {{ $post->e_title }}">
                  <img alt="{{ $post->e_title }}" class="w-full h-52 object-cover transition-transform duration-300 group-hover:scale-[1.02]" src="{{ $image }}" />
                  @if ((int) $post->is_video_only === 1)
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center pointer-events-none">
                      <span class="material-icons text-white text-6xl drop-shadow-lg">play_circle_filled</span>
                    </div>
                  @endif
                </a>
                <div class="absolute left-3 top-3">
                  <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow">
                    {{ (int) $post->is_video_only === 1 ? 'Video' : 'Article' }}
                  </span>
                </div>
              </div>
              <div class="p-6 flex flex-col flex-grow">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-3">{{ $post->e_title }}</h2>
                <div class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-4">
                  <img alt="{{ $creatorLabel }} avatar" class="w-6 h-6 rounded-full mr-2 object-cover" src="{{ $creator?->profile_image ? (filter_var($creator->profile_image, FILTER_VALIDATE_URL) ? $creator->profile_image : asset($creator->profile_image)) : 'https://ui-avatars.com/api/?name=' . urlencode($creatorLabel) . '&background=E2E8F0&color=0F172A' }}" />
                  <span>{{ $creatorLabel }}</span>
                  <span class="mx-2">•</span>
                  <span>{{ optional($post->created_at)->format('M d, Y') }}</span>
                </div>
                <p class="text-slate-600 dark:text-slate-300 mb-4 flex-grow">
                  {{ \Illuminate\Support\Str::limit($post->description ?: $post->e_text ?: 'No description yet.', 160) }}
                </p>
                <div class="flex items-center justify-between gap-3">
                  <a class="text-primary font-semibold hover:underline mt-auto self-start" href="{{ route('forum.show', $post->e_id) }}">Read More →</a>
                  <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $post->likes_count ?? 0 }} likes
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>

        <div class="mt-10">
          {{ $posts->links() }}
        </div>
      @endif
    </div>
  </section>
</main>

@if ($tab === 'contribute')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const root = document.querySelector('[data-contribute-tabs]');
      if (!root) return;

      const buttons = root.querySelectorAll('[data-contribute-tab-button]');
      const panels = root.querySelectorAll('[data-contribute-tab-panel]');
      const initialTab = root.dataset.initialTab || 'article';

      const setActiveTab = (activeTab) => {
        buttons.forEach((button) => {
          const isActive = button.dataset.contributeTabButton === activeTab;
          button.classList.toggle('bg-brand-blue-light', isActive);
          button.classList.toggle('text-white', isActive);
          button.classList.toggle('shadow-md', isActive);
          button.classList.toggle('border-transparent', isActive);
          button.classList.toggle('bg-transparent', !isActive);
          button.classList.toggle('text-slate-500', !isActive);
          button.classList.toggle('dark:text-slate-300', !isActive);
          button.classList.toggle('hover:bg-white/70', !isActive);
          button.classList.toggle('dark:hover:bg-slate-700/70', !isActive);
        });

        panels.forEach((panel) => {
          panel.classList.toggle('hidden', panel.dataset.contributeTabPanel !== activeTab);
        });
      };

      buttons.forEach((button) => {
        button.addEventListener('click', function () {
          setActiveTab(this.dataset.contributeTabButton);
        });
      });

      setActiveTab(initialTab);
    });
  </script>
@endif
@endsection
