@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Make a Wish')

@push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{ asset('js/create-wish.js') }}" defer></script>
@endpush

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-5xl mx-auto">
          <div class="flex items-center gap-3 mb-8">
            <span class="material-symbols-outlined text-3xl text-primary">auto_awesome</span>
            <div>
              <h1 class="text-3xl sm:text-4xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Make a Wish</h1>
              <p class="text-text-muted-light dark:text-text-muted-dark mt-1">Share your wish with the community so granters can help you make it real.</p>
            </div>
          </div>
          <div class="bg-surface-light dark:bg-surface-dark shadow-xl rounded-xl border border-border-light dark:border-border-dark/60">
            @if (session('status'))
              <div class="px-6 sm:px-8 pt-6">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                  {{ session('status') }}
                </div>
              </div>
            @endif
            @php
              $isEdit = isset($wish);
              $fundingValue = old('funding');
              if (!$fundingValue && isset($wish)) {
                if ((int) $wish->non_pay_option === 1) {
                  $fundingValue = 'no';
                } elseif (!empty($wish->financial_assistance) || (int) $wish->show_mail_status === 1) {
                  $fundingValue = 'yes';
                }
              }
            @endphp
            <form class="p-6 sm:p-8 space-y-8" id="wish-form" action="{{ $isEdit ? route('wishes.update', $wish->w_id) : route('wishes.store') }}" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              @if($isEdit)
                @method('PUT')
              @endif
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish-title">Wish Title <span class="text-red-500">*</span></label>
                <input id="wish-title" name="wish_title" type="text" required placeholder="Give your wish a clear title"
                  value="{{ old('wish_title', $wish->wish_title ?? '') }}"
                  class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                <p class="text-sm text-red-600 hidden" data-error-for="wish-title">Wish title is required.</p>
              </div>

              <div class="space-y-3">
                <div class="flex items-center gap-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish-description">Wish Description</label>
                  <span class="text-text-muted-light text-xs">(details help granters understand your need)</span>
                </div>
                <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden">
                  <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-[#0f172a] border-b border-border-light dark:border-border-dark text-text-muted-light dark:text-text-muted-dark">
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_bold</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_italic</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_list_bulleted</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">link</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">insert_photo</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">help_outline</span></button>
                  </div>
                  <textarea id="wish-description" name="wish_description" rows="6" placeholder="Describe what you wish for, why it matters, and any helpful details for granters."
                    class="w-full border-0 rounded-b-xl bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-0 resize-y">{{ old('wish_description', $wish->wish_description ?? '') }}</textarea>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div>
                    <p class="text-sm font-semibold text-text-light dark:text-text-dark">Wish Image</p>
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Upload your own photo or pick one of the default images below.</p>
                    <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">Use images you own the rights to. Posts with copyrighted characters or logos may be removed.</p>
                  </div>
                  <label class="inline-flex items-center gap-2 px-4 py-2 bg-brand-blue-light text-white rounded-lg shadow hover:opacity-90 cursor-pointer">
                    <span class="material-icons !text-base">file_upload</span>
                    <span>Choose an Image</span>
                    <input id="wish-image-upload" name="wish_image_upload" class="hidden" type="file" accept="image/*" />
                  </label>
                </div>
                <div id="upload-preview-wrapper" class="hidden">
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                      <p class="text-sm font-semibold text-text-light dark:text-text-dark">Uploaded image</p>
                      <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Use the controls to zoom in or out.</p>
                    </div>
                    <div class="flex items-center gap-3">
                      <button type="button" id="zoom-out" class="w-10 h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-brand-blue-light font-bold">-</button>
                      <input id="zoom-range" type="range" min="1" max="3" step="0.05" value="1.2" class="w-40 accent-brand-blue-light" />
                      <button type="button" id="zoom-in" class="w-10 h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-brand-blue-light font-bold">+</button>
                    </div>
                  </div>
                  <div class="mt-4 flex justify-center">
                    <div id="upload-preview-frame" class="relative w-72 h-72 bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden shadow-inner border border-border-light dark:border-border-dark cursor-grab active:cursor-grabbing select-none touch-none">
                      <img id="upload-preview-image" class="absolute inset-0 w-full h-full object-cover origin-center pointer-events-none" alt="Uploaded wish preview" />
                      <div class="absolute inset-6 border-2 border-white/90 pointer-events-none rounded-md"></div>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="wish_image_default" id="wish-image-default" value="{{ old('wish_image_default', $wish->primary_image ?? '') }}" />
                @php
                  $defaultWishImages = [];
                  $candidateDirectories = [
                      public_path('images/wishes-default'),
                      base_path('../public_html/images/wishes-default'),
                  ];

                  foreach ($candidateDirectories as $defaultWishDir) {
                      if (!is_dir($defaultWishDir)) {
                          continue;
                      }

                      foreach (\Illuminate\Support\Facades\File::files($defaultWishDir) as $file) {
                          $ext = strtolower($file->getExtension());
                          if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                              $defaultWishImages[] = 'images/wishes-default/' . $file->getFilename();
                          }
                      }

                      if ($defaultWishImages !== []) {
                          break;
                      }
                  }
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark p-3" data-default-grid>
                  @forelse ($defaultWishImages as $imagePath)
                    <button type="button" class="relative rounded-lg overflow-hidden border border-transparent hover:border-primary group cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary" data-default-item data-default-image="{{ asset($imagePath) }}">
                      <img class="w-full h-28 object-cover" src="{{ asset($imagePath) }}" alt="Default wish image" />
                      <span class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white font-semibold text-sm">Use Image</span>
                      <span class="absolute inset-0 bg-primary/35 opacity-0 transition" data-selected-overlay></span>
                      <span class="absolute top-2 right-2 bg-primary text-brand-blue-light rounded-full p-1 shadow-lg opacity-0 transition" data-selected-check>
                        <span class="material-icons !text-base text-brand-blue-light">check_circle</span>
                      </span>
                    </button>
                  @empty
                    <div class="col-span-full text-sm text-text-muted-light dark:text-text-muted-dark px-2 py-3">
                      No default images found in the wishes default image directory.
                    </div>
                  @endforelse
                </div>
              </div>

              <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish-date">Date - I would like my wish to be granted <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <span class="material-icons absolute left-3 top-3 text-text-muted-light">calendar_today</span>
                    <input id="wish-date" name="wish_date" type="text" required placeholder="YYYY-MM-DD"
                      value="{{ old('wish_date', $wish->expected_date ?? '') }}"
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark pl-11 focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                  </div>
                  <p class="text-sm text-red-600 hidden" data-error-for="wish-date">Date is required.</p>
                </div>
                <div class="space-y-2">
                  <p class="block text-sm font-semibold text-text-light dark:text-text-dark">Does your wish require direct funding? <span class="text-red-500">*</span></p>
                  <div class="flex items-center gap-6 text-sm">
                    <label class="inline-flex items-center gap-2">
                      <input class="text-primary focus:ring-primary" id="funding-yes" name="funding" type="radio" value="yes" required @checked($fundingValue === 'yes') />
                      <span>Yes (Financial)</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input class="text-primary focus:ring-primary" id="funding-no" name="funding" type="radio" value="no" required @checked($fundingValue === 'no') />
                      <span>No (Non-Financial)</span>
                    </label>
                  </div>
                  <p class="text-sm text-red-600 hidden" data-error-for="funding">Please select an option.</p>
                </div>
              </div>

              <div class="rounded-xl border border-border-light dark:border-border-dark bg-slate-50/80 dark:bg-surface-dark/60 p-6 space-y-5 hidden" id="financial-block">
                <div class="grid md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <p class="text-sm font-semibold text-text-light dark:text-text-dark">How would you like to give the financial assistance? <span class="text-red-500">*</span></p>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="paypal" @checked(old('payment', $wish->financial_assistance ?? '') === 'paypal') /> Paypal</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="venmo" @checked(old('payment', $wish->financial_assistance ?? '') === 'venmo') /> Venmo</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="cashapp" @checked(old('payment', $wish->financial_assistance ?? '') === 'cashapp') /> CashApp</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="google_apple_pay" @checked(old('payment', $wish->financial_assistance ?? '') === 'google_apple_pay') /> Google/Apple Pay</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="gofundme" @checked(old('payment', $wish->financial_assistance ?? '') === 'gofundme') /> GoFundMe</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="zelle" @checked(old('payment', $wish->financial_assistance ?? '') === 'zelle') /> Zelle</label>
                      <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="payment" type="radio" value="other" @checked(old('payment', $wish->financial_assistance ?? '') === 'other') /> Other</label>
                    </div>
                    <p class="text-sm text-red-600 hidden" data-error-for="payment">Please choose a financial assistance method.</p>
                  </div>
                  <div class="space-y-4">
                    <div class="space-y-2">
                      <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="contact">Please enter your email or handle: <span class="text-red-500">*</span></label>
                      <input id="contact" name="contact" type="email" required placeholder="you@example.com"
                        value="{{ old('contact', $wish->show_mail ?? '') }}"
                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                      <p class="text-sm text-red-600 hidden" data-error-for="contact">Email or handle is required.</p>
                    </div>
                    <div class="space-y-2">
                      <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="cost">Expected Cost (USD) <span class="text-red-500">*</span></label>
                      <div class="relative">
                        <span class="absolute left-3 top-3 text-text-muted-light">$</span>
                        <input id="cost" name="expected_cost" type="number" min="0" step="1" placeholder="250"
                          value="{{ old('expected_cost', $wish->expected_cost ?? '') }}"
                          class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark pl-8 focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="rounded-xl border border-border-light dark:border-border-dark bg-slate-50/80 dark:bg-surface-dark/60 p-6 space-y-5 hidden" id="non-financial-block">
                <div class="space-y-2">
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">How would you like to receive this Wish? <span class="text-red-500">*</span></p>
                  <div class="flex flex-wrap items-center gap-6 text-sm">
                    <label class="inline-flex items-center gap-2">
                      <input class="text-primary focus:ring-primary" name="non_financial_method" type="radio" value="online_order" @checked(old('non_financial_method', $wish->way_of_wish ?? '') === 'online_order') />
                      <span>Online order</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input class="text-primary focus:ring-primary" name="non_financial_method" type="radio" value="drop_off_pickup" @checked(old('non_financial_method', $wish->way_of_wish ?? '') === 'drop_off_pickup') />
                      <span>Drop-off/Pick up Location</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input class="text-primary focus:ring-primary" name="non_financial_method" type="radio" value="mail" @checked(old('non_financial_method', $wish->way_of_wish ?? '') === 'mail') />
                      <span>Send in the mail</span>
                    </label>
                  </div>
                  <p class="text-sm text-red-600 hidden" data-error-for="non_financial_method">Please choose how you would like to receive this wish.</p>
                </div>
                <div class="space-y-2">
                  <label id="non-financial-label" class="block text-sm font-semibold text-text-light dark:text-text-dark" for="non-financial-notes">Details <span class="text-red-500">*</span></label>
                  <textarea id="non-financial-notes" name="description_of_way" rows="4" placeholder="Add any delivery notes or details for the granter."
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary">{{ old('description_of_way', $wish->description_of_way ?? '') }}</textarea>
                  <p class="text-sm text-red-600 hidden" data-error-for="description_of_way">Please provide the details.</p>
                </div>
                <p id="dropoff-message" class="hidden text-sm text-red-600">
                  *We recommend that you always meet in a public space and bring a buddy whenever possible.
                </p>
              </div>

              <div class="space-y-3">
                <label class="inline-flex items-start gap-3 text-sm text-text-light dark:text-text-dark">
                  <input id="terms-check" name="i_agree_decide" class="mt-1 text-primary focus:ring-primary" type="checkbox" required @checked(old('i_agree_decide', $wish->i_agree_decide ?? false)) />
                  <span>I understand that once the Grantor accepts my Wish, they will have 14 days to fulfill it in the agreed upon manner by both of us. During that time my wish will be marked as "In Progress", until either I mark it as "Fulfilled" or 14 days pass. After 14 days my wish will be considered "Granted". I can re-submit a Wish if it does not get fulfilled by the Grantor after 14 days. <span class="text-red-500">*</span></span>
                </label>
                <p class="text-sm text-red-600 hidden" data-error-for="terms">Please acknowledge you accept this condition</p>
              </div>

              <div class="flex flex-wrap gap-3 pt-2">
                <button class="px-6 py-3 bg-brand-blue-light text-white rounded-lg font-semibold shadow hover:opacity-90 transition" type="submit" name="action" value="publish">Submit</button>
                <button class="px-6 py-3 bg-primary/20 text-brand-blue-light dark:text-primary rounded-lg font-semibold border border-primary/40 hover:bg-primary/30 transition" type="submit" name="action" value="draft">Save as Drafts</button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>
@endsection
