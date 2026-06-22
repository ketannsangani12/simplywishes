@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Donation Detail')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if (session('status'))
      <div class="max-w-5xl mx-auto mb-6">
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
          {{ session('status') }}
        </div>
      </div>
    @endif
    <div class="max-w-5xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-border-light dark:border-border-dark bg-slate-50/60 dark:bg-background-dark/60">
        <div class="flex items-center justify-between gap-4">
          <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark">
            {{ $donation->title ?: 'Untitled donation' }}
          </h1>
          <div class="flex items-center gap-2">
            @if((int) ($donation->created_by ?? 0) === (int) auth()->id() && in_array((int) $donation->status, [0, 1], true))
              <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary/20 text-brand-blue-light text-sm font-semibold hover:bg-primary/30 transition-colors" href="{{ route('donations.edit', $donation->id) }}">
                <span class="material-icons !text-base">edit</span>
                Update
              </a>
              <form action="{{ route('donations.destroy', $donation->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors" type="submit" onclick="return confirm('Delete this donation?');">
                  <span class="material-icons !text-base">delete</span>
                  Delete
                </button>
              </form>
            @endif
            <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition" href="{{ route('wishes.active') }}">
              <span class="material-icons !text-base">arrow_back</span>
              Back
            </a>
          </div>
        </div>
      </div>

      <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
          <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden bg-white dark:bg-surface-dark shadow-sm relative">
            @php
              $image = $donation->image ? asset($donation->image) : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80';
            @endphp
            <img alt="Donation image" class="w-full object-cover aspect-square" src="{{ $image }}" />
            <div class="absolute top-3 right-3 flex items-center gap-2">
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-primary transition js-activity {{ in_array($donation->id, $favDonationIds ?? [], true) ? 'ring-2 ring-primary/60 text-primary is-active' : '' }}" data-activity="fav" data-donation-id="{{ $donation->id }}" aria-label="Save donation" type="button">
                <span class="material-icons !text-base {{ in_array($donation->id, $favDonationIds ?? [], true) ? 'text-yellow-400' : '' }}">{{ in_array($donation->id, $favDonationIds ?? [], true) ? 'bookmark' : 'bookmark_border' }}</span>
              </button>
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-rose-500 transition js-activity {{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'ring-2 ring-rose-400/70 text-rose-500 is-active' : '' }}" data-activity="like" data-donation-id="{{ $donation->id }}" aria-label="Like donation" type="button">
                <span class="material-icons !text-base {{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'text-rose-500' : '' }}">{{ in_array($donation->id, $likeDonationIds ?? [], true) ? 'favorite' : 'favorite_border' }}</span>
              </button>
              <form action="{{ route('donations.report', $donation->id) }}" method="POST" class="js-content-report-form" data-report-label="donation" data-reported="{{ !empty($hasReportedDonation) ? 'true' : 'false' }}">
                @csrf
                <button class="w-9 h-9 rounded-full bg-white/90 shadow transition {{ !empty($hasReportedDonation) ? 'text-red-500 ring-2 ring-red-400/70 cursor-default' : 'text-slate-700 hover:bg-white hover:text-amber-500' }}" aria-label="Report donation" type="submit">
                  <span class="material-icons !text-base">flag</span>
                </button>
              </form>
              <div class="relative">
                <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-donation-id="{{ $donation->id }}" data-donation-title="{{ $donation->title ?: 'Donation' }}" aria-label="Share donation" type="button">
                  <span class="material-icons !text-base">share</span>
                </button>
                <div class="share-menu hidden absolute right-0 top-11 z-10 rounded-lg border border-border-light bg-white shadow-lg p-2">
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
        </div>

        <div class="lg:col-span-2 space-y-6 text-sm">
          <div>
            <p class="text-text-muted-light dark:text-text-muted-dark">Description</p>
            <p class="font-semibold">{{ $donation->description ?: 'No description yet.' }}</p>
          </div>

          @php
            $creatorName = $creator ? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) : '';
            $creatorName = $creatorName !== '' ? $creatorName : ($creator->name ?? 'Donation Creator');
            $isFinancial = (int) $donation->non_pay_option !== 1;
            $deliveryType = $donation->delivery_type ?: ($donation->way_of_donation ?: 'Not set');
            $deliveryTypeLabel = match ($donation->delivery_type ?: $donation->way_of_donation) {
                'online_order' => 'Online order',
                'drop_off_pickup' => 'Drop off / pickup',
                'mail' => 'Mail',
                null, '' => 'Not set',
                default => \Illuminate\Support\Str::headline((string) ($donation->delivery_type ?: $donation->way_of_donation)),
            };
            $isCreator = (int) ($donation->created_by ?? 0) === (int) auth()->id();
            $isPublished = (int) $donation->status === 1;
            $isInProgress = (int) $donation->status === 2;
            $acceptedByName = $acceptedBy ? trim(($acceptedBy->first_name ?? '') . ' ' . ($acceptedBy->last_name ?? '')) : '';
            $acceptedByName = $acceptedByName !== '' ? $acceptedByName : ($acceptedBy->name ?? 'Another user');
          @endphp
          <div class="flex flex-wrap items-center gap-3">
            @if($isPublished)
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                Published
              </span>
            @elseif($isInProgress)
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                In Progress
              </span>
            @elseif((int) $donation->status === 3)
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                Completed
              </span>
            @endif

            @if(!$isCreator && $isPublished)
              @if($isFinancial)
                <form action="{{ route('donations.accept', $donation->id) }}" method="POST">
                  @csrf
                  <button class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700 transition" type="submit">
                    <span class="material-icons !text-base">volunteer_activism</span>
                    Accept This Donation
                  </button>
                </form>
              @else
                <button class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700 transition js-open-non-financial-accept" type="button">
                  <span class="material-icons !text-base">volunteer_activism</span>
                  Accept This Donation
                </button>
              @endif
            @elseif($isCreator && $isInProgress)
              <form action="{{ route('donations.complete', $donation->id) }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-brand-blue-light text-white font-semibold shadow hover:opacity-90 transition" type="submit" onclick="return confirm('Mark this donation as completed?');">
                  <span class="material-icons !text-base">task_alt</span>
                  Complete Donation
                </button>
              </form>
              <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                Accepted by {{ $acceptedByName }}{{ $donation->accepted_at ? ' on ' . \Illuminate\Support\Carbon::parse($donation->accepted_at)->format('M j, Y g:i A') : '' }}.
              </div>
            @elseif($isInProgress)
              <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                Accepted by {{ $acceptedByName }}{{ $donation->accepted_at ? ' on ' . \Illuminate\Support\Carbon::parse($donation->accepted_at)->format('M j, Y g:i A') : '' }}.
              </div>
            @elseif((int) $donation->status === 3)
              <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 space-y-1">
                <div><span class="font-semibold">Donation accepted by:</span> {{ $acceptedByName }}</div>
                <div><span class="font-semibold">Donation accepted on:</span> {{ $donation->accepted_at ? \Illuminate\Support\Carbon::parse($donation->accepted_at)->format('M j, Y g:i A') : 'Not available' }}</div>
                @if($donation->completed_at)
                  <div><span class="font-semibold">Donation completed on:</span> {{ \Illuminate\Support\Carbon::parse($donation->completed_at)->format('M j, Y g:i A') }}</div>
                @endif
              </div>
            @endif
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <p class="text-text-muted-light dark:text-text-muted-dark">Creator</p>
              <p class="font-semibold">{{ $creatorName }}</p>
            </div>
            <div>
              <p class="text-text-muted-light dark:text-text-muted-dark">Donation Type</p>
              <p class="font-semibold">{{ $isFinancial ? 'Financial' : 'Non-Financial' }}</p>
            </div>
            @if($isFinancial)
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Donation Expected Cost</p>
                <p class="font-semibold">{{ $donation->expected_cost ? '$' . number_format($donation->expected_cost, 0) : 'Not set' }}</p>
              </div>
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Financial assistance</p>
                <p class="font-semibold">{{ $donation->financial_assistance ?: 'Not set' }}</p>
              </div>
            @else
              <div>
                <p class="text-text-muted-light dark:text-text-muted-dark">Delivery Type</p>
                <p class="font-semibold">{{ $deliveryTypeLabel }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-5xl mx-auto mt-8 bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60">
      <div class="p-6 sm:p-8 space-y-6">
        <h2 class="text-2xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Comments</h2>
        <div class="rounded-xl border border-border-light dark:border-border-dark p-4 bg-white dark:bg-surface-dark space-y-4">
          <form action="{{ route('donations.comments.store', $donation->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="flex items-start gap-4">
              @php
                $authUser = auth()->user();
                $authAvatar = $authUser?->profile_image ? asset($authUser->profile_image) : null;
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
                $commenterName = trim(($commentUser->first_name ?? '') . ' ' . ($commentUser->last_name ?? ''));
                $commenterName = $commenterName !== '' ? $commenterName : ($commentUser->name ?? 'User');
                $commentAvatar = $commentUser?->profile_image ? asset($commentUser->profile_image) : null;
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
                          <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="donation-edit-{{ $comment->id }}" aria-label="Edit comment">
                            <span class="material-icons !text-base">edit</span>
                          </button>
                          <form action="{{ route('donations.comments.destroy', [$donation->id, $comment->id]) }}" method="POST">
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
                      <form id="donation-edit-{{ $comment->id }}" action="{{ route('donations.comments.update', [$donation->id, $comment->id]) }}" method="POST" class="mt-4 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
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
                      <form action="{{ route('donations.comments.like', [$donation->id, $comment->id]) }}" method="POST">
                        @csrf
                        <button class="inline-flex items-center gap-1 {{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">
                          <span>Love</span>
                        </button>
                      </form>
                      <button class="inline-flex items-center gap-1 hover:text-primary js-reply-toggle" type="button" data-reply-target="donation-reply-{{ $comment->id }}">
                        <span>Reply</span>
                      </button>
                      <form action="{{ route('donations.comments.report', [$donation->id, $comment->id]) }}" method="POST" class="js-comment-report-form">
                        @csrf
                        <button class="inline-flex items-center gap-1 hover:text-amber-500" type="submit">
                          <span>Report</span>
                        </button>
                      </form>
                      <div class="inline-flex items-center gap-1 text-red-500">
                        <span class="material-icons !text-base">favorite</span>
                        <span class="font-semibold">{{ $comment->likes_count }}</span>
                      </div>
                    </div>

                    <form id="donation-reply-{{ $comment->id }}" action="{{ route('donations.comments.store', $donation->id) }}" method="POST" class="mt-4 hidden js-reply-form space-y-3 rounded-lg bg-slate-50 p-3">
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
                            $replyAvatar = $replyUser?->profile_image ? asset($replyUser->profile_image) : null;
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
                                    <button class="text-text-muted-light transition hover:text-primary js-edit-toggle" type="button" data-edit-target="donation-edit-{{ $reply->id }}" aria-label="Edit reply">
                                      <span class="material-icons !text-base">edit</span>
                                    </button>
                                    <form action="{{ route('donations.comments.destroy', [$donation->id, $reply->id]) }}" method="POST">
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
                                <form id="donation-edit-{{ $reply->id }}" action="{{ route('donations.comments.update', [$donation->id, $reply->id]) }}" method="POST" class="mt-3 hidden js-edit-form space-y-3 rounded-lg bg-slate-50 p-3">
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
                                <form action="{{ route('donations.comments.like', [$donation->id, $reply->id]) }}" method="POST">
                                  @csrf
                                  <button class="{{ in_array($reply->id, $likedCommentIds ?? [], true) ? 'text-red-500' : 'hover:text-red-500' }}" type="submit">Love</button>
                                </form>
                                <form action="{{ route('donations.comments.report', [$donation->id, $reply->id]) }}" method="POST" class="js-comment-report-form">
                                  @csrf
                                  <button class="hover:text-amber-500" type="submit">Report</button>
                                </form>
                                <div class="inline-flex items-center gap-1 text-red-500">
                                  <span class="material-icons !text-base">favorite</span>
                                  <span class="font-semibold">{{ $reply->likes_count }}</span>
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
  </section>
</main>

@if(!$isCreator && $isPublished && !$isFinancial)
  <div class="fixed inset-0 z-50 hidden js-non-financial-modal" aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/50 js-close-non-financial-modal"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
      <div class="w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
          <h2 class="text-2xl font-semibold text-slate-900">Non-financial</h2>
          <button class="text-slate-400 transition hover:text-slate-600 js-close-non-financial-modal" type="button" aria-label="Close">
            <span class="material-icons">close</span>
          </button>
        </div>
        <form action="{{ route('donations.accept', $donation->id) }}" method="POST" class="flex min-h-[32rem] flex-col">
          @csrf
          <div class="flex-1 space-y-6 px-6 py-5 text-slate-800">
            <div class="text-2xl font-medium text-amber-500">Accept this donation via the chosen delivery type(s): "{{ $deliveryTypeLabel }}"</div>
            @if($donation->description_of_way)
              <div class="text-lg">
                <span class="font-semibold">{{ $donation->description_of_way }}</span>
              </div>
            @endif
            <label class="flex items-start gap-3 text-[1.05rem] leading-8">
              <input class="mt-2 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary js-non-financial-agreement" type="checkbox" name="non_financial_agreement" value="1" />
              <span>I agree to accept this donation in the manner specified by the donor and within 10 days of the date that I accept it. In the meanwhile, this donation will be marked as "In Progress" and after 10 days, it will be marked as "Fulfilled". The donor should update or re-submit their donation if it has not been fulfilled after 10 days.</span>
            </label>
            @error('non_financial_agreement')
              <p class="text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
          </div>
          <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
            <button class="rounded-md border border-slate-300 px-4 py-2 text-base text-slate-700 transition hover:bg-slate-50 js-close-non-financial-modal" type="button">Cancel</button>
            <button class="rounded-md bg-blue-600 px-5 py-2 text-base font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-blue-300 js-non-financial-submit" type="submit" disabled>Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    document.querySelectorAll('.js-activity').forEach((button) => {
      button.addEventListener('click', async () => {
        const donationId = button.getAttribute('data-donation-id');
        const type = button.getAttribute('data-activity');
        if (!donationId || !type) return;
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
            body: JSON.stringify({ donation_id: donationId, type }),
          });
          if (res.ok) {
            const icon = button.querySelector('.material-icons');
            if (type === 'fav') {
              button.classList.toggle('is-active', !isActive);
              button.classList.toggle('ring-2', !isActive);
              button.classList.toggle('ring-primary/60', !isActive);
              button.classList.toggle('text-primary', !isActive);
              if (icon) {
                icon.classList.toggle('text-yellow-400', !isActive);
                icon.textContent = isActive ? 'bookmark_border' : 'bookmark';
              }
            }
            if (type === 'like') {
              button.classList.toggle('is-active', !isActive);
              button.classList.toggle('ring-2', !isActive);
              button.classList.toggle('ring-rose-400/70', !isActive);
              button.classList.toggle('text-rose-500', !isActive);
              if (icon) {
                icon.classList.toggle('text-rose-500', !isActive);
                icon.textContent = isActive ? 'favorite_border' : 'favorite';
              }
            }
          }
        } catch (e) {
          // ignore client errors for now
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
        const donationId = shareBtn?.getAttribute('data-donation-id');
        const donationTitle = shareBtn?.getAttribute('data-donation-title') || 'Donation';
        if (!donationId) return;
        const url = `${window.location.origin}/donations/${donationId}`;
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(`Check out this donation: ${donationTitle}`);

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

    const nonFinancialModal = document.querySelector('.js-non-financial-modal');
    const openNonFinancialModalButton = document.querySelector('.js-open-non-financial-accept');
    const nonFinancialAgreement = document.querySelector('.js-non-financial-agreement');
    const nonFinancialSubmit = document.querySelector('.js-non-financial-submit');

    const toggleNonFinancialSubmit = () => {
      if (!nonFinancialAgreement || !nonFinancialSubmit) {
        return;
      }

      nonFinancialSubmit.disabled = !nonFinancialAgreement.checked;
    };

    const closeNonFinancialModal = () => {
      if (!nonFinancialModal) {
        return;
      }

      nonFinancialModal.classList.add('hidden');
      nonFinancialModal.setAttribute('aria-hidden', 'true');
    };

    const openNonFinancialModal = () => {
      if (!nonFinancialModal) {
        return;
      }

      nonFinancialModal.classList.remove('hidden');
      nonFinancialModal.setAttribute('aria-hidden', 'false');
      toggleNonFinancialSubmit();
    };

    openNonFinancialModalButton?.addEventListener('click', openNonFinancialModal);
    nonFinancialAgreement?.addEventListener('change', toggleNonFinancialSubmit);

    document.querySelectorAll('.js-close-non-financial-modal').forEach((button) => {
      button.addEventListener('click', closeNonFinancialModal);
    });

    if (nonFinancialModal) {
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          closeNonFinancialModal();
        }
      });
    }

    @if($errors->has('non_financial_agreement'))
      openNonFinancialModal();
    @endif

    document.querySelectorAll('.js-reply-toggle').forEach((button) => {
      button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-reply-target');
        if (!targetId) {
          return;
        }

        const target = document.getElementById(targetId);
        if (!target) {
          return;
        }

        target.classList.toggle('hidden');
      });
    });

    document.querySelectorAll('.js-edit-toggle').forEach((button) => {
      button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-edit-target');
        if (!targetId) {
          return;
        }

        const target = document.getElementById(targetId);
        if (!target) {
          return;
        }

        target.classList.toggle('hidden');
      });
    });

    @if(old('parent_id'))
      document.getElementById('donation-reply-{{ old('parent_id') }}')?.classList.remove('hidden');
    @endif

    @if(old('edit_comment_id'))
      document.getElementById('donation-edit-{{ old('edit_comment_id') }}')?.classList.remove('hidden');
    @endif

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
  });
</script>
@endsection
