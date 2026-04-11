@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Make a Donation')

@push('head')
  <script src="{{ asset('js/create-donation.js') }}" defer></script>
@endpush

@section('content')
<main class="flex-1 bg-gradient-to-b from-white via-white to-slate-50 dark:from-background-dark dark:via-background-dark dark:to-background-dark">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-5xl mx-auto">
          <div class="flex items-center gap-3 mb-8">
            <span class="material-symbols-outlined text-3xl text-primary">volunteer_activism</span>
            <div>
              <h1 class="text-3xl sm:text-4xl font-bold text-brand-blue-light dark:text-brand-blue-dark">Make a Donation</h1>
              <p class="text-text-muted-light dark:text-text-muted-dark mt-1">Describe your donation so a wisher can accept it and coordinate fulfillment.</p>
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
            <form class="p-6 sm:p-8 space-y-8" id="donation-form" action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="donation-title">Donation Title <span class="text-red-500">*</span></label>
                <input id="donation-title" name="donation_title" type="text" placeholder="Give your donation a clear title"
                  class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                <p class="text-sm text-red-600 hidden" data-error-for="donation-title">Donation title is required.</p>
              </div>

              <div class="space-y-3">
                <div class="flex items-center gap-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="donation-description">Donation Description</label>
                  <span class="text-text-muted-light text-xs">(share item details, condition, timing, and any notes)</span>
                </div>
                <div class="rounded-xl border border-border-light dark:border-border-dark overflow-hidden">
                  <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-[#0f172a] border-b border-border-light dark:border-border-dark text-text-muted-light dark:text-text-muted-dark">
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">undo</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">redo</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_bold</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_italic</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_underlined</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">format_list_bulleted</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">link</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">insert_photo</span></button>
                    <button class="p-2 rounded hover:bg-white dark:hover:bg-surface-dark" type="button"><span class="material-icons">help_outline</span></button>
                  </div>
                  <textarea id="donation-description" name="donation_description" rows="6" placeholder="Describe the donation, how and when you can provide it, and any requirements for pickup or delivery."
                    class="w-full border-0 rounded-b-xl bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-0 resize-y"></textarea>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div>
                    <p class="text-sm font-semibold text-text-light dark:text-text-dark">Donation Image</p>
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Upload your own photo or pick one of the default images below.</p>
                    <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">Use images you own the rights to. Posts with copyrighted characters or logos may be removed.</p>
                  </div>
                  <label class="inline-flex items-center gap-2 px-4 py-2 bg-brand-blue-light text-white rounded-lg shadow hover:opacity-90 cursor-pointer">
                    <span class="material-icons !text-base">file_upload</span>
                    <span>Choose an Image</span>
                    <input id="donation-image-upload" name="donation_image_upload" class="hidden" type="file" accept="image/*" />
                  </label>
                </div>
                <div id="donation-upload-preview-wrapper" class="hidden">
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                      <p class="text-sm font-semibold text-text-light dark:text-text-dark">Uploaded image</p>
                      <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Use the controls to zoom in or out.</p>
                    </div>
                    <div class="flex items-center gap-3">
                      <button type="button" id="donation-zoom-out" class="w-10 h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-brand-blue-light font-bold">-</button>
                      <input id="donation-zoom-range" type="range" min="1" max="3" step="0.05" value="1.2" class="w-40 accent-brand-blue-light" />
                      <button type="button" id="donation-zoom-in" class="w-10 h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-brand-blue-light font-bold">+</button>
                    </div>
                  </div>
                  <div class="mt-4 flex justify-center">
                    <div id="donation-upload-preview-frame" class="relative w-72 h-72 bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden shadow-inner border border-border-light dark:border-border-dark cursor-grab active:cursor-grabbing select-none touch-none">
                      <img id="donation-upload-preview-image" class="absolute inset-0 w-full h-full object-cover origin-center pointer-events-none" alt="Uploaded donation preview" />
                      <div class="absolute inset-6 border-2 border-white/90 pointer-events-none rounded-md"></div>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="donation_image_default" id="donation-image-default" />
                @php
                  $defaultDonationImages = [];
                  $defaultDonationDir = public_path('images/wishes-default');
                  if (is_dir($defaultDonationDir)) {
                      foreach (\Illuminate\Support\Facades\File::files($defaultDonationDir) as $file) {
                          $ext = strtolower($file->getExtension());
                          if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                              $defaultDonationImages[] = 'images/wishes-default/' . $file->getFilename();
                          }
                      }
                  }
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark p-3" data-donation-default-grid>
                  @forelse ($defaultDonationImages as $imagePath)
                    <button type="button" class="relative rounded-lg overflow-hidden border border-transparent hover:border-primary group cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary" data-donation-default-item data-default-image="{{ asset($imagePath) }}">
                      <img class="w-full h-28 object-cover" src="{{ asset($imagePath) }}" alt="Default donation image" />
                      <span class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white font-semibold text-sm">Use Image</span>
                      <span class="absolute inset-0 bg-primary/35 opacity-0 transition" data-selected-overlay></span>
                      <span class="absolute top-2 right-2 bg-primary text-brand-blue-light rounded-full p-1 shadow-lg opacity-0 transition" data-selected-check>
                        <span class="material-icons !text-base text-brand-blue-light">check_circle</span>
                      </span>
                    </button>
                  @empty
                    <div class="col-span-full text-sm text-text-muted-light dark:text-text-muted-dark px-2 py-3">
                      No default images found in `public/images/wishes-default`.
                    </div>
                  @endforelse
                </div>
              </div>

              <div class="space-y-2">
                <p class="block text-sm font-semibold text-text-light dark:text-text-dark">Does your Donation require direct funding? <span class="text-red-500">*</span></p>
                <div class="flex items-center gap-6 text-sm">
                  <label class="inline-flex items-center gap-2">
                    <input class="text-primary focus:ring-primary" id="donation-funding-yes" name="donation_funding" type="radio" value="yes" />
                    <span>Yes (Financial)</span>
                  </label>
                  <label class="inline-flex items-center gap-2">
                    <input class="text-primary focus:ring-primary" id="donation-funding-no" name="donation_funding" type="radio" value="no" />
                    <span>No (Non-Financial)</span>
                  </label>
                </div>
                <p class="text-sm text-red-600 hidden" data-error-for="donation-funding">Please select an option.</p>
              </div>

              <div class="rounded-xl border border-border-light dark:border-border-dark bg-slate-50/80 dark:bg-surface-dark/60 p-6 space-y-5 hidden" id="donation-financial-block">
                <div class="space-y-2">
                  <p class="text-sm font-semibold text-text-light dark:text-text-dark">How would you like to give the financial assistance? <span class="text-red-500">*</span></p>
                  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-2 text-sm">
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="paypal" /> Paypal</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="venmo" /> Venmo</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="cashapp" /> CashApp</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="google_apple_pay" /> Google/Apple Pay</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="gofundme" /> GoFundMe</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="zelle" /> Zelle</label>
                    <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_payment" type="radio" value="other" /> Other</label>
                  </div>
                  <p class="text-sm text-red-600 hidden" data-error-for="donation-payment">Please choose a financial assistance method.</p>
                </div>
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="donation-cost">Expected Cost (USD) <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <span class="absolute left-3 top-3 text-text-muted-light">$</span>
                    <input id="donation-cost" name="expected_cost" type="number" min="0" step="1" placeholder="250"
                      class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark pl-8 focus:ring-2 focus:ring-primary/60 focus:border-primary" />
                  </div>
                  <p class="text-sm text-red-600 hidden" data-error-for="donation-cost">Expected cost is required.</p>
                </div>
              </div>

              <div class="rounded-xl border border-border-light dark:border-border-dark bg-slate-50/80 dark:bg-surface-dark/60 p-6 space-y-4 hidden" id="donation-method-block">
                <p class="text-sm font-semibold text-text-light dark:text-text-dark">How would you like to give this Donation? <span class="text-red-500">*</span></p>
                <div class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                  <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_method" type="radio" value="online_order" /> Online order</label>
                  <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_method" type="radio" value="drop_off_pickup" /> Drop-off/Pick up Location</label>
                  <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_method" type="radio" value="mail" /> Send in the mail</label>
                  <label class="inline-flex items-center gap-2"><input class="text-primary focus:ring-primary" name="donation_method" type="radio" value="other" /> Other</label>
                </div>
                <p class="text-sm text-red-600 hidden" data-error-for="donation-method">Please choose a donation method.</p>
                <div class="space-y-2">
                  <label id="donation-method-label" class="block text-sm font-semibold text-text-light dark:text-text-dark" for="donation-notes">Add details for your selected method <span class="text-red-500">*</span></label>
                  <textarea id="donation-notes" name="donation_notes" rows="4" placeholder="Example: I can ship within 3 days, or meet within 5 miles of downtown."
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y"></textarea>
                </div>
              </div>

              <div class="space-y-3">
                <label class="inline-flex items-start gap-3 text-sm text-text-light dark:text-text-dark">
                  <input id="donation-terms" name="donation_terms" class="mt-1 text-primary focus:ring-primary" type="checkbox" />
                  <span>I understand that once the donation is accepted by a Wisher, I have 14 days to fulfill it. In offering this Donation, I agree to work with the Wisher to fulfill this Donation within 14 days, during which time this Donation will be marked as In Progress. After 14 days, it will be marked as Fulfilled. I will not be able to delete or update my donation once someone has accepted my offer. <span class="text-red-500">*</span></span>
                </label>
                <p class="text-sm text-red-600 hidden" data-error-for="donation-terms">Please acknowledge you accept this condition.</p>
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
