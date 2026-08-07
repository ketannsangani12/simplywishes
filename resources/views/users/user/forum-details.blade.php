@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Forum Details')

@php
  $creator = $post->creator;
  $creatorName = trim(implode(' ', array_filter([$creator?->first_name ?? null, $creator?->last_name ?? null]))) ?: ($creator?->name ?: 'Simply Wishes');
  $creatorAvatar = $creator?->profile_image
    ? (filter_var($creator->profile_image, FILTER_VALIDATE_URL) ? $creator->profile_image : asset($creator->profile_image))
    : 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=E2E8F0&color=0F172A';
  $postImage = $post->imageUrl();
  $postVideoUrl = $post->videoUrl();
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
        @if ($postImage && empty($post->featured_video_url))
          <div class="relative">
            <img alt="{{ $post->e_title }}" class="w-full max-h-[520px] object-cover" src="{{ $postImage }}" />
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
            <div class="flex items-center gap-3">
              <form method="POST" action="{{ route('forum.like', $post->e_id) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold {{ $likedByCurrentUser ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                  <span class="material-icons text-base">favorite</span>
                  Love
                  <span class="text-xs font-bold">{{ $postLikesCount }}</span>
                </button>
              </form>
              @if ((int) ($post->created_by ?? 0) === (int) auth()->id())
                <a href="{{ route('forum.edit', $post->e_id) }}" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200">
                  <span class="material-icons text-base">edit</span>
                  Edit
                </a>
                <form action="{{ route('forum.destroy', $post->e_id) }}" method="POST" onsubmit="return confirm('Delete this forum post? This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold bg-red-50 text-red-600 hover:bg-red-100" aria-label="Delete forum">
                    <span class="material-icons text-base">delete</span>
                    Delete
                  </button>
                </form>
              @else
                <form action="{{ route('forum.report', $post->e_id) }}" method="POST" class="js-content-report-form" data-report-label="forum" data-reported="{{ !empty($hasReportedForum) ? 'true' : 'false' }}">
                  @csrf
                  <button type="submit" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold bg-slate-100 shadow transition {{ !empty($hasReportedForum) ? 'text-red-500 ring-2 ring-red-400/70 cursor-default' : 'text-slate-700 hover:bg-slate-200 hover:text-amber-500' }}" aria-label="Report forum">
                    <span class="material-icons text-base">flag</span>
                    Report
                  </button>
                </form>
              @endif
              <div class="relative">
                <button class="w-10 h-10 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-forum-id="{{ $post->e_id }}" data-forum-title="{{ $post->e_title }}" aria-label="Share forum" type="button">
                  <span class="material-icons !text-base">share</span>
                </button>
                <div class="share-menu hidden absolute right-0 top-12 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
                  <div class="flex items-center gap-2">
                    <button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="facebook" aria-label="Share on Facebook" title="Facebook">
                      <svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1877F2]" aria-hidden="true"><path d="M22 12.06C22 6.57 17.52 2 12 2S2 6.57 2 12.06c0 4.96 3.66 9.06 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.88 8.44-4.98 8.44-9.94z"/></svg>
                    </button>
                    <button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="twitter" aria-label="Share on Twitter" title="Twitter">
                      <svg viewBox="0 0 24 24" class="w-4 h-4 fill-[#1DA1F2]" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 0 0 1.88-2.36 8.6 8.6 0 0 1-2.72 1.04 4.26 4.26 0 0 0-7.3 3.89A12.1 12.1 0 0 1 3.15 4.6a4.26 4.26 0 0 0 1.32 5.68 4.2 4.2 0 0 1-1.93-.53v.05a4.26 4.26 0 0 0 3.42 4.18 4.28 4.28 0 0 1-1.92.07 4.26 4.26 0 0 0 3.98 2.96A8.55 8.55 0 0 1 2 19.54 12.07 12.07 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0 0 22.46 6z"/></svg>
                    </button>
                    <button class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center" type="button" data-share-channel="instagram" aria-label="Copy link for Instagram" title="Instagram">
                      <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                        <path fill="#E1306C" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3z"/>
                        <path fill="#E1306C" d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2.2a2.8 2.8 0 1 1 0 5.6 2.8 2.8 0 0 1 0-5.6z"/>
                        <circle cx="17.5" cy="6.5" r="1.2" fill="#E1306C"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
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

        <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
          <form action="{{ route('forum.comments.store', $post->e_id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="flex items-start gap-4">
              @php
                $authUser = auth()->user();
                $authAvatar = $authUser?->profile_image ? (filter_var($authUser->profile_image, FILTER_VALIDATE_URL) ? $authUser->profile_image : asset($authUser->profile_image)) : null;
              @endphp
              @if($authAvatar)
                <img alt="Your avatar" class="h-10 w-10 rounded-full object-cover" src="{{ $authAvatar }}" />
              @else
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">
                  {{ strtoupper(substr($authUser->first_name ?? $authUser->name ?? 'U', 0, 1)) }}
                </div>
              @endif
              <div class="flex-1 space-y-3">
                <textarea name="comment" rows="3" class="w-full rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y px-4 py-3" placeholder="Add a comment...">{{ old('comment') }}</textarea>
                @error('comment')
                  <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="flex justify-end">
                  <button class="px-5 py-2 bg-brand-blue-light text-white font-semibold rounded-lg shadow hover:opacity-90 transition" type="submit">Post</button>
                </div>
              </div>
            </div>
          </form>

          <div class="space-y-5 border-t border-border-light dark:border-border-dark pt-4">
            @forelse($comments as $comment)
              @php
                $commentUser = $comment->user;
                $commentName = trim(($commentUser->first_name ?? '') . ' ' . ($commentUser->last_name ?? ''));
                $commentName = $commentName !== '' ? $commentName : ($commentUser->name ?? 'User');
                $commentAvatar = $commentUser?->profile_image
                  ? (filter_var($commentUser->profile_image, FILTER_VALIDATE_URL) ? $commentUser->profile_image : asset($commentUser->profile_image))
                  : null;
              @endphp
              <article class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark">
                <div class="flex items-start gap-4">
                  @if($commentAvatar)
                    <img alt="{{ $commentName }} avatar" class="h-10 w-10 rounded-full object-cover" src="{{ $commentAvatar }}" />
                  @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">
                      {{ strtoupper(substr($commentName, 0, 1)) }}
                    </div>
                  @endif
                  <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <h3 class="font-semibold text-primary">{{ $commentName }}</h3>
                        <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $comment->created_at?->format('F j Y g:i a') }}</div>
                      </div>
                      @if((int) $comment->user_id === (int) auth()->id())
                        <div class="flex items-center gap-2">
                          <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="forum-edit-{{ $comment->id }}" aria-label="Edit comment">
                            <span class="material-icons !text-base">edit</span>
                          </button>
                          <form action="{{ route('forum.comments.destroy', [$post->e_id, $comment->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-text-muted-light transition hover:text-red-500" type="submit" aria-label="Delete comment" onclick="return confirm('Delete this comment?');">
                              <span class="material-icons !text-base">delete</span>
                            </button>
                          </form>
                        </div>
                      @endif
                    </div>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-text-light dark:text-text-dark">{{ $comment->comment }}</p>
                    @if((int) $comment->user_id === (int) auth()->id())
                      <form id="forum-edit-{{ $comment->id }}" action="{{ route('forum.comments.update', [$post->e_id, $comment->id]) }}" method="POST" class="mt-4 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
                        @csrf
                        @method('PUT')
                        <textarea name="comment" rows="3" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60">{{ old('edit_comment_id') == $comment->id ? old('comment') : $comment->comment }}</textarea>
                        <input type="hidden" name="edit_comment_id" value="{{ $comment->id }}" />
                        <div class="flex justify-end">
                          <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition" type="submit">Update</button>
                        </div>
                      </form>
                    @endif
                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                      <form action="{{ route('forum.comments.like', [$post->e_id, $comment->id]) }}" method="POST">
                        @csrf
                        <button class="inline-flex items-center gap-1 {{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">
                          <span>Love</span>
                        </button>
                      </form>
                      <button class="inline-flex items-center gap-1 hover:text-primary js-reply-toggle" type="button" data-reply-target="forum-reply-{{ $comment->id }}">
                        <span>Reply</span>
                      </button>
                      @if((int) $comment->user_id !== (int) auth()->id())
                        <form action="{{ route('forum.comments.report', [$post->e_id, $comment->id]) }}" method="POST" class="js-comment-report-form">
                          @csrf
                          <button class="inline-flex items-center gap-1 hover:text-amber-500" type="submit">
                            <span>Report</span>
                          </button>
                        </form>
                      @endif
                      <div class="inline-flex items-center gap-1 text-red-500">
                        <span class="material-icons !text-base">favorite</span>
                        <span class="font-semibold">{{ $comment->likes->count() }}</span>
                      </div>
                    </div>

                    <form id="forum-reply-{{ $comment->id }}" action="{{ route('forum.comments.store', $post->e_id) }}" method="POST" class="mt-4 hidden js-reply-form space-y-3 rounded-lg bg-slate-50 p-3">
                      @csrf
                      <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                      <textarea name="comment" rows="2" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60" placeholder="Write a reply...">{{ old('parent_id') == $comment->id ? old('comment') : '' }}</textarea>
                      <div class="flex justify-end">
                        <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition" type="submit">Reply</button>
                      </div>
                    </form>

                    @if($comment->replies->isNotEmpty())
                      <div class="mt-4 space-y-4 border-l-2 border-slate-100 pl-4">
                        @foreach($comment->replies as $reply)
                          @php
                            $replyUser = $reply->user;
                            $replyName = trim(($replyUser->first_name ?? '') . ' ' . ($replyUser->last_name ?? ''));
                            $replyName = $replyName !== '' ? $replyName : ($replyUser->name ?? 'User');
                            $replyAvatar = $replyUser?->profile_image
                              ? (filter_var($replyUser->profile_image, FILTER_VALIDATE_URL) ? $replyUser->profile_image : asset($replyUser->profile_image))
                              : null;
                          @endphp
                          <div class="flex items-start gap-3">
                            @if($replyAvatar)
                              <img alt="{{ $replyName }} avatar" class="h-9 w-9 rounded-full object-cover" src="{{ $replyAvatar }}" />
                            @else
                              <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold text-slate-600">
                                {{ strtoupper(substr($replyName, 0, 1)) }}
                              </div>
                            @endif
                            <div class="min-w-0 flex-1">
                              <div class="flex items-start justify-between gap-3">
                                <div>
                                  <h4 class="font-semibold text-primary">{{ $replyName }}</h4>
                                  <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $reply->created_at?->format('F j Y g:i a') }}</div>
                                </div>
                                @if((int) $reply->user_id === (int) auth()->id())
                                  <div class="flex items-center gap-2">
                                    <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="forum-edit-{{ $reply->id }}" aria-label="Edit reply">
                                      <span class="material-icons !text-base">edit</span>
                                    </button>
                                    <form action="{{ route('forum.comments.destroy', [$post->e_id, $reply->id]) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button class="text-text-muted-light transition hover:text-red-500" type="submit" aria-label="Delete reply" onclick="return confirm('Delete this reply?');">
                                        <span class="material-icons !text-base">delete</span>
                                      </button>
                                    </form>
                                  </div>
                                @endif
                              </div>
                              <p class="mt-2 whitespace-pre-line text-sm leading-6 text-text-light dark:text-text-dark">{{ $reply->comment }}</p>
                              @if((int) $reply->user_id === (int) auth()->id())
                                <form id="forum-edit-{{ $reply->id }}" action="{{ route('forum.comments.update', [$post->e_id, $reply->id]) }}" method="POST" class="mt-3 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
                                  @csrf
                                  @method('PUT')
                                  <textarea name="comment" rows="3" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60">{{ old('edit_comment_id') == $reply->id ? old('comment') : $reply->comment }}</textarea>
                                  <input type="hidden" name="edit_comment_id" value="{{ $reply->id }}" />
                                  <div class="flex justify-end">
                                    <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition" type="submit">Update</button>
                                  </div>
                                </form>
                              @endif
                              <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-text-muted-light dark:text-text-muted-dark">
                                <form action="{{ route('forum.comments.like', [$post->e_id, $reply->id]) }}" method="POST">
                                  @csrf
                                  <button class="{{ in_array($reply->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">Love</button>
                                </form>
                                @if((int) $reply->user_id !== (int) auth()->id())
                                  <form action="{{ route('forum.comments.report', [$post->e_id, $reply->id]) }}" method="POST" class="js-comment-report-form">
                                    @csrf
                                    <button class="hover:text-amber-500" type="submit">Report</button>
                                  </form>
                                @endif
                                <div class="inline-flex items-center gap-1 text-red-500">
                                  <span class="material-icons !text-base">favorite</span>
                                  <span class="font-semibold">{{ $reply->likes->count() }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @endif
                  </div>
                </div>
              </article>
            @empty
              <div class="rounded-xl border border-dashed border-border-light dark:border-border-dark p-6 text-center text-text-muted-light dark:text-text-muted-dark">
                No comments yet.
              </div>
            @endforelse
          </div>
        </div>
      </section>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-reply-toggle').forEach((button) => {
      button.addEventListener('click', function () {
        const targetId = button.getAttribute('data-reply-target');
        const target = targetId ? document.getElementById(targetId) : null;
        if (!target) return;
        target.classList.toggle('hidden');
        const textarea = target.querySelector('textarea');
        if (textarea && !target.classList.contains('hidden')) {
          textarea.focus();
        }
      });
    });

    document.querySelectorAll('.js-edit-toggle').forEach((button) => {
      button.addEventListener('click', function () {
        const targetId = button.getAttribute('data-edit-target');
        const target = targetId ? document.getElementById(targetId) : null;
        if (!target) return;
        target.classList.toggle('hidden');
        const textarea = target.querySelector('textarea');
        if (textarea && !target.classList.contains('hidden')) {
          textarea.focus();
        }
      });
    });

    document.querySelectorAll('.js-comment-report-form').forEach((form) => {
      form.addEventListener('submit', function (event) {
        const confirmed = window.confirm("Are you sure you want to report this comment?\n\nReporting this comment will hide this and any other comments from this author as well as the author's profile from you and will trigger a full review by SimplyWishes.");
        if (!confirmed) {
          event.preventDefault();
        }
      });
    });

    document.querySelectorAll('.js-content-report-form').forEach((form) => {
      form.addEventListener('submit', function (event) {
        if (form.getAttribute('data-reported') === 'true') {
          event.preventDefault();
          return;
        }

        const label = form.getAttribute('data-report-label') || 'item';
        const confirmed = window.confirm(`Are you sure you want to report this ${label}?\n\nReporting this ${label} will trigger a full review by SimplyWishes.`);
        if (!confirmed) {
          event.preventDefault();
        }
      });
    });

    const closeShareMenus = () => {
      document.querySelectorAll('.share-menu').forEach((menu) => menu.classList.add('hidden'));
    };

    document.querySelectorAll('.js-share-btn').forEach((button) => {
      button.addEventListener('click', (event) => {
        event.stopPropagation();
        const menu = button.parentElement?.querySelector('.share-menu');
        if (!menu) return;
        const isHidden = menu.classList.contains('hidden');
        closeShareMenus();
        if (isHidden) {
          menu.classList.remove('hidden');
        }
      });
    });

    document.querySelectorAll('.share-menu [data-share-channel]').forEach((item) => {
      item.addEventListener('click', async (event) => {
        event.stopPropagation();
        const channel = item.getAttribute('data-share-channel');
        const wrapper = item.closest('.share-menu')?.parentElement;
        const shareBtn = wrapper?.querySelector('.js-share-btn');
        const forumId = shareBtn?.getAttribute('data-forum-id');
        const forumTitle = shareBtn?.getAttribute('data-forum-title') || 'Forum';
        if (!forumId) return;

        const url = `${window.location.origin}/forum/${forumId}`;
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(`Check out this forum post: ${forumTitle}`);

        if (channel === 'facebook') {
          window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`, '_blank', 'noopener,noreferrer');
        } else if (channel === 'twitter') {
          window.open(`https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`, '_blank', 'noopener,noreferrer');
        } else if (channel === 'instagram') {
          try {
            await navigator.clipboard.writeText(url);
            alert('Link copied. Paste it in Instagram.');
          } catch (e) {
            window.prompt('Copy this link:', url);
          }
        }

        closeShareMenus();
      });
    });

    document.addEventListener('click', () => closeShareMenus());
  });
</script>
@endsection
