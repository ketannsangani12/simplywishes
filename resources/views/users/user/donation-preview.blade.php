@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Donation Detail')

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-5xl mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark/60 overflow-hidden">
      <div class="p-6 sm:p-8 border-b border-border-light dark:border-border-dark bg-slate-50/60 dark:bg-background-dark/60">
        <div class="flex items-center justify-between gap-4">
          <h1 class="text-2xl sm:text-3xl font-bold text-brand-blue-light dark:text-brand-blue-dark">
            {{ $donation->title ?: 'Untitled donation' }}
          </h1>
          <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition" href="{{ route('wishes.active') }}">
            <span class="material-icons !text-base">arrow_back</span>
            Back
          </a>
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
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-amber-500 transition" aria-label="Report donation" type="button">
                <span class="material-icons !text-base">flag</span>
              </button>
              <button class="w-9 h-9 rounded-full bg-white/90 text-slate-700 shadow hover:bg-white hover:text-sky-500 transition js-share-btn" data-donation-id="{{ $donation->id }}" data-donation-title="{{ $donation->title ?: 'Donation' }}" aria-label="Share donation" type="button">
                <span class="material-icons !text-base">share</span>
              </button>
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
          @endphp
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
                <p class="font-semibold">{{ $deliveryType }}</p>
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
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-slate-200"></div>
            <div class="flex-1 space-y-3">
              <textarea rows="3" class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y" placeholder="Add a comment..."></textarea>
              <div class="flex justify-end">
                <button class="px-5 py-2 bg-brand-blue-light text-white font-semibold rounded-lg shadow hover:opacity-90 transition">Post</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
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
  });
</script>
@endsection
