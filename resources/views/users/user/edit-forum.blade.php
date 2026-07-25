@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@php
  $isVideo = (int) ($post->is_video_only ?? 0) === 1;
  $titleValue = old($isVideo ? 'video_title' : 'article_title', $post->e_title);
  $contentValue = old($isVideo ? 'video_content' : 'article_content', $post->description ?: $post->e_text);
  $videoUrlValue = old($isVideo ? 'video_featured_video_url' : 'article_featured_video_url', filter_var($post->featured_video_url, FILTER_VALIDATE_URL) ? $post->featured_video_url : '');
  $thumbnailUrl = $post->e_image ? (filter_var($post->e_image, FILTER_VALIDATE_URL) ? $post->e_image : asset($post->e_image)) : null;
  $uploadedVideoUrl = $post->featured_video_url && ! filter_var($post->featured_video_url, FILTER_VALIDATE_URL) ? asset($post->featured_video_url) : null;
@endphp

@section('title', $isVideo ? 'Edit Forum Video' : 'Edit Forum Article')

@section('content')
<main class="flex-grow bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <div class="max-w-4xl mx-auto space-y-8">
      <div>
        <a href="{{ route('forum.show', $post->e_id) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
          <span class="material-icons text-base">arrow_back</span>
          Back to post
        </a>
        <h1 class="mt-4 text-3xl font-bold text-brand-blue-light dark:text-white">{{ $isVideo ? 'Edit Forum Video' : 'Edit Forum Article' }}</h1>
        <p class="mt-2 text-text-muted-light dark:text-text-muted-dark">Update your post details and media.</p>
      </div>

      @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-900 dark:bg-red-950/50 dark:text-red-200">
          <p class="font-semibold">Please fix the following errors:</p>
          <ul class="mt-2 list-disc pl-5 space-y-1 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md p-6 md:p-8">
        <form class="space-y-6" method="POST" action="{{ route('forum.update', $post->e_id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          @if ($isVideo)
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-title">Title <span class="text-red-500">*</span></label>
              <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="video-title" name="video_title" value="{{ $titleValue }}" type="text" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-description">Description <span class="text-red-500">*</span></label>
              <textarea class="w-full h-40 rounded-md border border-slate-300 p-3 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="video-description" name="video_content">{{ $contentValue }}</textarea>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="youtube-url">Video URL</label>
                  <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="youtube-url" name="video_featured_video_url" value="{{ $videoUrlValue }}" placeholder="http:// or https://" type="url" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="video-file">Replace video file</label>
                  <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="video-file" name="video_featured_video_file" type="file" />
                  @if ($uploadedVideoUrl)
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Current uploaded video is attached to this post.</p>
                  @endif
                </div>
              </div>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="thumbnail-image">Replace thumbnail image</label>
                  <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="thumbnail-image" name="video_thumbnail" type="file" />
                </div>
                @if ($thumbnailUrl)
                  <div>
                    <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">Current thumbnail</p>
                    <img src="{{ $thumbnailUrl }}" alt="Current forum thumbnail" class="h-40 w-full rounded-xl object-cover border border-slate-200 dark:border-slate-700" />
                  </div>
                @endif
              </div>
            </div>
          @else
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-title">Title <span class="text-red-500">*</span></label>
              <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-title" name="article_title" value="{{ $titleValue }}" type="text" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-content">Article content <span class="text-red-500">*</span></label>
              <textarea class="w-full h-48 rounded-md border border-slate-300 p-3 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-content" name="article_content">{{ $contentValue }}</textarea>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-url">Video URL</label>
                  <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-video-url" name="article_featured_video_url" value="{{ $videoUrlValue }}" placeholder="http:// or https://" type="url" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-file">Replace video file</label>
                  <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="article-video-file" name="article_featured_video_file" type="file" />
                </div>
              </div>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-video-thumbnail">Replace thumbnail image</label>
                  <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="article-video-thumbnail" name="article_thumbnail" type="file" />
                </div>
                @if ($thumbnailUrl)
                  <div>
                    <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">Current thumbnail</p>
                    <img src="{{ $thumbnailUrl }}" alt="Current forum thumbnail" class="h-40 w-full rounded-xl object-cover border border-slate-200 dark:border-slate-700" />
                  </div>
                @endif
              </div>
            </div>
          @endif

          <div class="flex items-center justify-end gap-3">
            <a href="{{ route('forum.show', $post->e_id) }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
              Cancel
            </a>
            <button class="bg-primary text-white font-bold py-2.5 px-6 rounded-lg hover:bg-primary/90 transition-colors" type="submit">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>
@endsection
