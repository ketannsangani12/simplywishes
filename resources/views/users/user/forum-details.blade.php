@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Forum Details')

@php
  $creator = $post->creator;
  $creatorName = trim(implode(' ', array_filter([$creator?->first_name ?? null, $creator?->last_name ?? null]))) ?: ($creator?->name ?: 'Simply Wishes');
  $creatorAvatar = $creator?->profile_image
    ? (filter_var($creator->profile_image, FILTER_VALIDATE_URL) ? $creator->profile_image : asset($creator->profile_image))
    : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
  $postImage = $post->e_image ?: $post->article_image;
  $postImage = $postImage ? (filter_var($postImage, FILTER_VALIDATE_URL) ? $postImage : asset($postImage)) : null;
  $postVideoUrl = $post->featured_video_url ? (filter_var($post->featured_video_url, FILTER_VALIDATE_URL) ? $post->featured_video_url : asset($post->featured_video_url)) : null;
  $postLikesCount = $post->likes_count ?? $post->likes->count();
@endphp

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <a href="{{ route('forum') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
          <span class="material-icons text-base">arrow_back</span>
          Back to forum
        </a>
        <h1 class="mt-4 text-2xl sm:text-4xl font-bold text-brand-blue-light dark:text-brand-blue-dark">{{ $post->e_title }}</h1>
      </div>

      <article class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl shadow-sm overflow-hidden">
        @if ($postImage)
          <div class="relative">
            <img alt="{{ $post->e_title }}" class="w-full max-h-[520px] object-cover" src="{{ $postImage }}" />
            @if ((int) $post->is_video_only === 1 && $post->featured_video_url)
              <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                <a class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/90 text-primary shadow-lg" href="{{ $postVideoUrl }}" target="_blank" rel="noopener">
                  <span class="material-icons text-4xl">play_arrow</span>
                </a>
              </div>
            @endif
          </div>
        @endif

        <div class="p-6 sm:p-8 space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex items-center gap-3">
              <img alt="{{ $creatorName }} avatar" class="h-12 w-12 rounded-full object-cover" src="{{ $creatorAvatar }}" />
              <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">By</p>
                <p class="font-semibold text-brand-blue-light dark:text-brand-blue-dark">{{ $creatorName }}</p>
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">{{ optional($post->created_at)->format('M d, Y g:i a') }}</p>
              </div>
            </div>
            <form method="POST" action="{{ route('forum.like', $post->e_id) }}">
              @csrf
              <button type="submit" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold {{ $likedByCurrentUser ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <span class="material-icons text-base">favorite</span>
                Love
                <span class="text-xs font-bold">{{ $postLikesCount }}</span>
              </button>
            </form>
          </div>

          <div class="prose max-w-none dark:prose-invert prose-slate">
            <p>{{ $post->description ?: $post->e_text }}</p>
          </div>

          @if ($post->featured_video_url)
            <div class="rounded-2xl overflow-hidden border border-border-light dark:border-border-dark">
              <div class="aspect-video bg-slate-100 dark:bg-slate-900">
                @php
                  $videoUrl = $post->featured_video_url;
                  $isYouTube = preg_match('/youtube\\.com\\/watch\\?v=([^&]+)/', $videoUrl) || preg_match('/youtu\\.be\\/([^?]+)/', $videoUrl);
                @endphp
                @if ($isYouTube)
                  @php
                    $embedUrl = $videoUrl;
                    if (preg_match('/youtube\\.com\\/watch\\?v=([^&]+)/', $videoUrl, $matches)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    } elseif (preg_match('/youtu\\.be\\/([^?]+)/', $videoUrl, $matches)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    }
                  @endphp
                  <iframe class="w-full h-full" src="{{ $embedUrl }}" title="{{ $post->e_title }}" allowfullscreen></iframe>
                @else
                  <video class="w-full h-full bg-black" controls>
                    <source src="{{ $postVideoUrl }}" />
                  </video>
                @endif
              </div>
            </div>
          @endif
        </div>
      </article>

      <section class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
          <span class="text-sm text-text-muted-light dark:text-text-muted-dark">{{ $comments->count() }} comments</span>
        </div>

        <form method="POST" action="{{ route('forum.comments.store', $post->e_id) }}" class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark">
          @csrf
          <textarea name="comment" rows="3" class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y" placeholder="Add a comment..."></textarea>
          @error('comment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
          <div class="flex justify-end mt-3">
            <button class="px-5 py-2 bg-brand-blue-light text-white font-semibold rounded-lg shadow hover:opacity-90 transition" type="submit">Post</button>
          </div>
        </form>

        <div class="space-y-5">
          @forelse ($comments as $comment)
            @php
              $commentUser = $comment->user;
              $commentName = $commentUser ? trim(implode(' ', array_filter([$commentUser->first_name ?? null, $commentUser->last_name ?? null]))) ?: ($commentUser->name ?: 'Member') : 'Member';
              $commentAvatar = $commentUser?->profile_image
                ? (filter_var($commentUser->profile_image, FILTER_VALIDATE_URL) ? $commentUser->profile_image : asset($commentUser->profile_image))
                : 'https://ui-avatars.com/api/?name=' . urlencode($commentName) . '&background=E2E8F0&color=0F172A';
              $commentLiked = in_array((int) $comment->id, array_map('intval', $likedCommentIds), true);
            @endphp

            <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
              <div class="flex items-start gap-4">
                <img alt="{{ $commentName }} avatar" class="w-10 h-10 rounded-full object-cover" src="{{ $commentAvatar }}" />
                <div class="flex-1">
                  <div class="flex items-center justify-between gap-3">
                    <div class="space-y-1">
                      <p class="font-semibold text-primary">{{ $commentName }}</p>
                      <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ optional($comment->created_at)->format('M d, Y g:i a') }}</div>
                    </div>
                    <div class="flex items-center gap-2 text-text-muted-light dark:text-text-muted-dark">
                      @if ((int) $comment->user_id === (int) auth()->id())
                        <form method="POST" action="{{ route('forum.comments.destroy', [$post->e_id, $comment->id]) }}">
                          @csrf
                          @method('DELETE')
                          <button class="hover:text-red-500" type="submit" aria-label="Delete"><span class="material-icons !text-base">delete</span></button>
                        </form>
                      @endif
                    </div>
                  </div>
                  <p class="mt-2 text-text-light dark:text-text-dark">{{ $comment->comment }}</p>
                  <div class="mt-3 flex items-center gap-4 text-sm text-text-muted-light dark:text-text-muted-dark">
                    <form method="POST" action="{{ route('forum.comments.like', [$post->e_id, $comment->id]) }}">
                      @csrf
                      <button class="inline-flex items-center gap-1 hover:text-red-500" type="submit">
                        <span class="material-icons !text-base {{ $commentLiked ? 'text-red-500' : '' }}">favorite</span>
                        <span>Love</span>
                        <span class="font-semibold text-red-500">{{ $comment->likes->count() }}</span>
                      </button>
                    </form>
                  </div>

                  @if ((int) $comment->user_id === (int) auth()->id())
                    <details class="mt-4">
                      <summary class="cursor-pointer text-sm font-semibold text-primary">Edit</summary>
                      <form method="POST" action="{{ route('forum.comments.update', [$post->e_id, $comment->id]) }}" class="mt-3 space-y-3">
                        @csrf
                        @method('PUT')
                        <textarea name="comment" rows="3" class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y">{{ $comment->comment }}</textarea>
                        <div class="flex justify-end">
                          <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition" type="submit">Update</button>
                        </div>
                      </form>
                    </details>
                  @endif
                </div>
              </div>

              @if ($comment->replies->isNotEmpty())
                <div class="ml-12 space-y-3 border-l border-border-light dark:border-border-dark pl-4">
                  @foreach ($comment->replies as $reply)
                    @php
                      $replyUser = $reply->user;
                      $replyName = $replyUser ? trim(implode(' ', array_filter([$replyUser->first_name ?? null, $replyUser->last_name ?? null]))) ?: ($replyUser->name ?: 'Member') : 'Member';
                      $replyAvatar = $replyUser?->profile_image
                        ? (filter_var($replyUser->profile_image, FILTER_VALIDATE_URL) ? $replyUser->profile_image : asset($replyUser->profile_image))
                        : 'https://ui-avatars.com/api/?name=' . urlencode($replyName) . '&background=E2E8F0&color=0F172A';
                    @endphp
                    <div class="flex items-start gap-3">
                      <img alt="{{ $replyName }} avatar" class="w-9 h-9 rounded-full object-cover" src="{{ $replyAvatar }}" />
                      <div class="flex-1 rounded-lg bg-slate-50 dark:bg-slate-900 p-3">
                        <div class="flex items-center justify-between">
                          <div>
                            <p class="font-semibold text-primary">{{ $replyName }}</p>
                            <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ optional($reply->created_at)->format('M d, Y g:i a') }}</div>
                          </div>
                        </div>
                        <p class="mt-2 text-text-light dark:text-text-dark">{{ $reply->comment }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          @empty
            <div class="rounded-xl border border-dashed border-border-light dark:border-border-dark p-6 text-center text-text-muted-light dark:text-text-muted-dark">
              No comments yet.
            </div>
          @endforelse
        </div>
      </section>
    </div>
  </section>
</main>
@endsection
