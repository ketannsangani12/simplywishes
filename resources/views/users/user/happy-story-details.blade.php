@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Happy Story')

@php
  $storyImage = function ($story) {
    $image = $story->story_image;

    if (! $image) {
      return 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=1200&q=80';
    }

    return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
  };

  $authorName = function ($story) {
    $user = $story->user;

    if (! $user) {
      return 'Simply Wishes';
    }

    $name = trim(implode(' ', array_filter([$user->first_name ?? null, $user->last_name ?? null])));

    return $name !== '' ? $name : ($user->name ?: 'Member');
  };

  $avatarUrl = function ($user, $name) {
    if (! $user?->profile_image) {
      return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=E2E8F0&color=0F172A';
    }

    return filter_var($user->profile_image, FILTER_VALIDATE_URL) ? $user->profile_image : asset($user->profile_image);
  };
@endphp

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if (session('status'))
      <div class="max-w-6xl mx-auto mb-6">
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
          {{ session('status') }}
        </div>
      </div>
    @endif

    <div class="max-w-6xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-border-light dark:border-border-dark bg-slate-50/60 dark:bg-background-dark/60">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
          <div class="space-y-2">
            <p class="text-sm uppercase tracking-wide text-text-muted-light dark:text-text-muted-dark">Happy Story</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark">
              {{ $story->wish?->wish_title ?: 'Happy Story' }}
            </h1>
            <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted-light dark:text-text-muted-dark">
              <span>By {{ $authorName($story) }}</span>
              <span class="h-1 w-1 rounded-full bg-current opacity-40"></span>
              <span>{{ optional($story->created_at)->format('F j, Y g:i a') }}</span>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition" href="{{ route('happy.stories') }}">
              <span class="material-icons !text-base">arrow_back</span>
              Back
            </a>
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-slate-700 shadow-sm ring-1 ring-border-light hover:bg-slate-50 transition js-activity {{ in_array($story->hs_id, $likedStoryIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-happy-story-id="{{ $story->hs_id }}" aria-label="Like happy story" type="button">
              <span class="material-icons !text-base {{ in_array($story->hs_id, $likedStoryIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($story->hs_id, $likedStoryIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
              <span>Like</span>
              <span class="font-semibold" data-like-count>{{ $storyLikeCount ?? 0 }}</span>
            </button>
            <div class="relative">
              <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-slate-700 shadow-sm ring-1 ring-border-light hover:bg-slate-50 transition js-share-btn" data-happy-story-id="{{ $story->hs_id }}" data-happy-story-title="{{ $story->wish?->wish_title ?: 'Happy Story' }}" aria-label="Share happy story" type="button">
                <span class="material-icons !text-base">share</span>
                Share
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
            @if((int) $story->user_id !== (int) auth()->id())
              <form action="{{ route('happy.stories.report', $story->hs_id) }}" method="POST" class="js-content-report-form" data-report-label="happy story" data-reported="{{ !empty($hasReportedStory) ? 'true' : 'false' }}">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ !empty($hasReportedStory) ? 'bg-red-50 text-red-500 ring-2 ring-red-400/70 cursor-default pointer-events-none' : 'bg-amber-50 text-amber-600 hover:bg-amber-100' }} text-sm font-semibold transition" type="submit" {{ !empty($hasReportedStory) ? 'disabled' : '' }}>
                  <span class="material-icons !text-base">flag</span>
                  {{ !empty($hasReportedStory) ? 'Reported' : 'Report' }}
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>

      <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
          <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden bg-white dark:bg-surface-dark shadow-sm">
            <img alt="Happy story image" class="w-full object-cover aspect-square" src="{{ $storyImage($story) }}" />
          </div>

          <div class="mt-4 rounded-xl border border-border-light dark:border-border-dark bg-slate-50 dark:bg-slate-900/40 p-4 space-y-3">
            <div class="flex items-center gap-3">
              <img alt="{{ $authorName($story) }}" class="h-12 w-12 rounded-full object-cover" src="{{ $avatarUrl($story->user, $authorName($story)) }}" />
              <div>
                <p class="font-semibold text-text-light dark:text-text-dark">{{ $authorName($story) }}</p>
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Story author</p>
              </div>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-text-muted-light dark:text-text-muted-dark">Related wish</p>
              <p class="font-semibold text-text-light dark:text-text-dark">{{ $story->wish?->wish_title ?: 'Untitled wish' }}</p>
            </div>
          </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
          <div class="rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark p-5 sm:p-6">
            <p class="text-text-muted-light dark:text-text-muted-dark text-sm">Story</p>
            <p class="mt-2 whitespace-pre-line text-base leading-7 text-text-light dark:text-text-dark">{{ $story->story_text }}</p>
          </div>

          <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60">
            <div class="p-6 sm:p-8 space-y-6">
              <div class="flex items-center justify-between gap-3">
                <h2 class="text-2xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
                <span class="text-sm text-text-muted-light dark:text-text-muted-dark">{{ $comments->count() }} comments</span>
              </div>

              <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
                <form action="{{ route('happy.stories.comments.store', $story->hs_id) }}" method="POST" class="space-y-4">
                  @csrf
                  <div class="flex items-start gap-4">
                    @php
                      $authUser = auth()->user();
                      $authAvatar = $authUser?->profile_image ? (filter_var($authUser->profile_image, FILTER_VALIDATE_URL) ? $authUser->profile_image : asset($authUser->profile_image)) : null;
                      $authName = trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? ''));
                      $authName = $authName !== '' ? $authName : ($authUser->name ?? 'User');
                    @endphp
                    @if($authAvatar)
                      <img alt="Your avatar" class="h-10 w-10 rounded-full object-cover" src="{{ $authAvatar }}" />
                    @else
                      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">
                        {{ strtoupper(substr($authName, 0, 1)) }}
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
                      $commenterName = trim(($commentUser->first_name ?? '') . ' ' . ($commentUser->last_name ?? ''));
                      $commenterName = $commenterName !== '' ? $commenterName : ($commentUser->name ?? 'User');
                      $commentAvatar = $commentUser?->profile_image ? (filter_var($commentUser->profile_image, FILTER_VALIDATE_URL) ? $commentUser->profile_image : asset($commentUser->profile_image)) : null;
                    @endphp
                    <article class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark">
                      <div class="flex items-start gap-4">
                        @if($commentAvatar)
                          <img alt="{{ $commenterName }} avatar" class="h-10 w-10 rounded-full object-cover" src="{{ $commentAvatar }}" />
                        @else
                          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">
                            {{ strtoupper(substr($commenterName, 0, 1)) }}
                          </div>
                        @endif
                        <div class="min-w-0 flex-1">
                          <div class="flex items-start justify-between gap-3">
                            <div>
                              <h3 class="font-semibold text-primary">{{ $commenterName }}</h3>
                              <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $comment->created_at?->format('F j Y g:i a') }}</div>
                            </div>
                            @if((int) $comment->user_id === (int) auth()->id())
                              <div class="flex items-center gap-2">
                                <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="story-edit-{{ $comment->id }}" aria-label="Edit comment">
                                  <span class="material-icons !text-base">edit</span>
                                </button>
                                <form action="{{ route('happy.stories.comments.destroy', [$story->hs_id, $comment->id]) }}" method="POST">
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
                            <form id="story-edit-{{ $comment->id }}" action="{{ route('happy.stories.comments.update', [$story->hs_id, $comment->id]) }}" method="POST" class="mt-4 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
                              @csrf
                              @method('PUT')
                              <textarea name="comment" rows="3" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60">{{ old('edit_comment_id') == $comment->id ? old('comment') : $comment->comment }}</textarea>
                              <input type="hidden" name="edit_comment_id" value="{{ $comment->id }}" />
                              <div class="flex justify-end gap-2">
                                <button type="button" class="px-4 py-2 rounded-lg border border-border-light text-sm font-semibold js-edit-cancel">Cancel</button>
                                <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold" type="submit">Save</button>
                              </div>
                            </form>
                          @endif

                          <div class="mt-3 flex flex-wrap items-center gap-4 text-sm font-medium text-text-muted-light dark:text-text-muted-dark">
                            <form action="{{ route('happy.stories.comments.like', [$story->hs_id, $comment->id]) }}" method="POST">
                              @csrf
                              <button class="inline-flex items-center gap-1 {{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">
                                <span class="material-icons !text-base">{{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
                                Love
                              </button>
                            </form>
                            <button class="inline-flex items-center gap-1 hover:text-primary js-reply-toggle" type="button" data-reply-target="story-reply-{{ $comment->id }}">
                              <span class="material-icons !text-base">reply</span>
                              Reply
                            </button>
                            <form action="{{ route('happy.stories.comments.report', [$story->hs_id, $comment->id]) }}" method="POST" class="js-comment-report-form">
                              @csrf
                              <button class="inline-flex items-center gap-1 hover:text-amber-500" type="submit">
                                <span class="material-icons !text-base">flag</span>
                                Report
                              </button>
                            </form>
                            <div class="ml-auto inline-flex items-center gap-1 text-rose-500">
                              <span class="material-icons !text-base">favorite</span>
                              <span class="font-semibold">{{ $comment->likes_count }}</span>
                            </div>
                          </div>

                          <form id="story-reply-{{ $comment->id }}" action="{{ route('happy.stories.comments.store', $story->hs_id) }}" method="POST" class="mt-4 hidden js-reply-form space-y-3 rounded-lg bg-slate-50 p-3">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                            <textarea name="comment" rows="2" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60" placeholder="Write a reply...">{{ old('parent_id') == $comment->id ? old('comment') : '' }}</textarea>
                            <div class="flex justify-end gap-2">
                              <button type="button" class="px-4 py-2 rounded-lg border border-border-light text-sm font-semibold js-reply-cancel">Cancel</button>
                              <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold" type="submit">Reply</button>
                            </div>
                          </form>

                          @if($comment->replies->isNotEmpty())
                            <div class="mt-4 space-y-3 border-l-2 border-border-light pl-4">
                              @foreach($comment->replies as $reply)
                                @php
                                  $replyUser = $reply->user;
                                  $replyName = trim(($replyUser->first_name ?? '') . ' ' . ($replyUser->last_name ?? ''));
                                  $replyName = $replyName !== '' ? $replyName : ($replyUser->name ?? 'User');
                                  $replyAvatar = $replyUser?->profile_image ? (filter_var($replyUser->profile_image, FILTER_VALIDATE_URL) ? $replyUser->profile_image : asset($replyUser->profile_image)) : null;
                                @endphp
                                <div class="rounded-xl border border-border-light dark:border-border-dark p-3 bg-slate-50 dark:bg-slate-900/40">
                                  <div class="flex items-start gap-3">
                                    @if($replyAvatar)
                                      <img alt="{{ $replyName }} avatar" class="h-9 w-9 rounded-full object-cover" src="{{ $replyAvatar }}" />
                                    @else
                                      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold text-slate-600">
                                        {{ strtoupper(substr($replyName, 0, 1)) }}
                                      </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                      <div class="flex items-start justify-between gap-2">
                                        <div>
                                          <h4 class="font-semibold text-primary">{{ $replyName }}</h4>
                                          <div class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $reply->created_at?->format('F j Y g:i a') }}</div>
                                        </div>
                                        @if((int) $reply->user_id === (int) auth()->id())
                                          <div class="flex items-center gap-2">
                                            <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="story-edit-{{ $reply->id }}" aria-label="Edit reply">
                                              <span class="material-icons !text-base">edit</span>
                                            </button>
                                            <form action="{{ route('happy.stories.comments.destroy', [$story->hs_id, $reply->id]) }}" method="POST">
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
                                        <form id="story-edit-{{ $reply->id }}" action="{{ route('happy.stories.comments.update', [$story->hs_id, $reply->id]) }}" method="POST" class="mt-3 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
                                          @csrf
                                          @method('PUT')
                                          <textarea name="comment" rows="3" class="w-full rounded-lg border border-border-light bg-white px-4 py-3 text-sm text-text-light focus:border-primary focus:ring-2 focus:ring-primary/60">{{ old('edit_comment_id') == $reply->id ? old('comment') : $reply->comment }}</textarea>
                                          <input type="hidden" name="edit_comment_id" value="{{ $reply->id }}" />
                                          <div class="flex justify-end gap-2">
                                            <button type="button" class="px-4 py-2 rounded-lg border border-border-light text-sm font-semibold js-edit-cancel">Cancel</button>
                                            <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold" type="submit">Save</button>
                                          </div>
                                        </form>
                                      @endif

                                      <div class="mt-3 flex flex-wrap items-center gap-4 text-sm font-medium text-text-muted-light dark:text-text-muted-dark">
                                        <form action="{{ route('happy.stories.comments.like', [$story->hs_id, $reply->id]) }}" method="POST">
                                          @csrf
                                          <button class="inline-flex items-center gap-1 {{ in_array($reply->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">
                                            <span class="material-icons !text-base">{{ in_array($reply->id, $likedCommentIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
                                            Love
                                          </button>
                                        </form>
                                        <form action="{{ route('happy.stories.comments.report', [$story->hs_id, $reply->id]) }}" method="POST" class="js-comment-report-form">
                                          @csrf
                                          <button class="inline-flex items-center gap-1 hover:text-amber-500" type="submit">
                                            <span class="material-icons !text-base">flag</span>
                                            Report
                                          </button>
                                        </form>
                                        <div class="ml-auto inline-flex items-center gap-1 text-rose-500">
                                          <span class="material-icons !text-base">favorite</span>
                                          <span class="font-semibold">{{ $reply->likes_count }}</span>
                                        </div>
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
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">No comments yet. Start the conversation.</p>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('.js-activity').forEach((button) => {
      button.addEventListener('click', async () => {
        const happyStoryId = button.getAttribute('data-happy-story-id');
        const type = button.getAttribute('data-activity');
        if (!happyStoryId || !type) return;

        try {
          const isActive = button.classList.contains('is-active');
          const endpoint = isActive ? '{{ route('activities.destroy') }}' : '{{ route('activities.store') }}';
          const method = isActive ? 'DELETE' : 'POST';
          const res = await fetch(endpoint, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token || '',
              'Accept': 'application/json',
            },
            body: JSON.stringify({ happy_story_id: happyStoryId, type }),
          });

            if (res.ok) {
              const icon = button.querySelector('.material-icons');
              const count = button.querySelector('[data-like-count]');
              const currentCount = Number.parseInt(count?.textContent || '0', 10);
              button.classList.toggle('is-active', !isActive);
              button.classList.toggle('ring-2', !isActive);
              button.classList.toggle('ring-rose-400/70', !isActive);
              button.classList.toggle('text-rose-500', !isActive);
              if (icon) {
                icon.classList.toggle('text-rose-500', !isActive);
                icon.textContent = isActive ? 'favorite_border' : 'favorite';
              }
              if (count) {
                count.textContent = String(isActive ? Math.max(0, currentCount - 1) : currentCount + 1);
              }
            }
        } catch (e) {
          // ignore client-side errors
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
        const storyId = shareBtn?.getAttribute('data-happy-story-id');
        const storyTitle = shareBtn?.getAttribute('data-happy-story-title') || 'Happy Story';
        if (!storyId) return;
        const url = `${window.location.origin}/happy-stories/${storyId}`;
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(`Check out this happy story: ${storyTitle}`);

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

    document.querySelectorAll('.js-edit-toggle').forEach((button) => {
      button.addEventListener('click', () => {
        const target = document.getElementById(button.getAttribute('data-edit-target'));
        if (!target) return;
        target.classList.toggle('hidden');
      });
    });

    document.querySelectorAll('.js-edit-cancel').forEach((button) => {
      button.addEventListener('click', () => {
        button.closest('.js-edit-form')?.classList.add('hidden');
      });
    });

    document.querySelectorAll('.js-reply-toggle').forEach((button) => {
      button.addEventListener('click', () => {
        const target = document.getElementById(button.getAttribute('data-reply-target'));
        if (!target) return;
        target.classList.toggle('hidden');
      });
    });

    document.querySelectorAll('.js-reply-cancel').forEach((button) => {
      button.addEventListener('click', () => {
        button.closest('.js-reply-form')?.classList.add('hidden');
      });
    });

    document.querySelectorAll('.js-comment-report-form').forEach((form) => {
      form.addEventListener('submit', (event) => {
        const confirmed = window.confirm("Are you sure you want to report this comment?\n\nReporting this comment will hide this and any other comments from this author as well as the author's profile from you and will trigger a full review by SimplyWishes.");
        if (!confirmed) {
          event.preventDefault();
        }
      });
    });

    document.querySelectorAll('.js-content-report-form').forEach((form) => {
      form.addEventListener('submit', (event) => {
        const reported = form.dataset.reported === 'true';
        if (reported) {
          event.preventDefault();
          return;
        }

        const label = form.dataset.reportLabel || 'content';
        const confirmed = window.confirm(`Are you sure you want to report this ${label}?`);
        if (!confirmed) {
          event.preventDefault();
        }
      });
    });

    @if(old('edit_comment_id'))
      document.getElementById('story-edit-{{ old('edit_comment_id') }}')?.classList.remove('hidden');
    @endif

    @if(old('parent_id'))
      document.getElementById('story-reply-{{ old('parent_id') }}')?.classList.remove('hidden');
    @endif
  });
</script>
@endsection
