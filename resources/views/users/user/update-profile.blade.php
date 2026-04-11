@extends('layouts.app', ['bodyClass' => 'bg-background-light text-text-light font-sans antialiased', 'headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Update Profile')

@section('content')
<main class="flex-1">
      <section class="relative overflow-hidden py-10 sm:py-14">
        <div class="absolute inset-0 bg-gradient-to-br from-[#e0f2fe] via-white to-[#fef9c3] opacity-70"></div>
        <div class="container relative mx-auto px-4 sm:px-6 lg:px-8">
          <div class="max-w-5xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-glow">
            <div class="p-6 sm:p-8 border-b border-gray-100">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                  <p class="text-sm font-semibold text-text-muted-light uppercase tracking-wide">Account settings</p>
                  <h1 class="text-3xl font-display font-bold text-brand-blue-light">Update profile</h1>
                  <p class="text-sm text-text-muted-light">Keep your details fresh so the community can reach you.</p>
                </div>
                <div class="flex gap-3">
                  <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-brand-blue-light hover:border-primary hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-base">undo</span>
                    Reset
                  </button>
                  <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors">
                    <span class="material-symbols-outlined text-base">save</span>
                    Save changes
                  </button>
                </div>
              </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
              <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="text-sm font-semibold text-text-light" for="account-email">Email <span class="text-red-500">*</span></label>
                  <input id="account-email" type="email" value="ketansangani12@gmail.com"
                    class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="first-name">First name <span class="text-red-500">*</span></label>
                    <input id="first-name" type="text" value="Ketan"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                  </div>
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="last-name">Last name <span class="text-red-500">*</span></label>
                    <input id="last-name" type="text" value="S"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                  </div>
                </div>
                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-semibold text-text-light" for="about-me">About me</label>
                  <textarea id="about-me" rows="3"
                    class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Tell the community a little about yourself">👍👌💛😘</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:col-span-2">
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="country">Country <span class="text-red-500">*</span></label>
                    <select id="country"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option>Australia</option>
                      <option>United States</option>
                      <option>United Kingdom</option>
                      <option>Canada</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="state">State <span class="text-red-500">*</span></label>
                    <select id="state"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option>Bankstown</option>
                      <option>New South Wales</option>
                      <option>Victoria</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="city">City <span class="text-red-500">*</span></label>
                    <select id="city"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option>Bankstown</option>
                      <option>Sydney</option>
                      <option>Melbourne</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="grid lg:grid-cols-[280px_1fr] gap-6">
                <div class="space-y-4">
                  <div class="space-y-2">
                    <p class="text-sm font-semibold text-text-light">Profile image</p>
                    <div class="w-52 h-52 rounded-2xl overflow-hidden border-2 border-primary/50 bg-gray-50 shadow-sm">
                      <img class="w-full h-full object-cover" alt="Current profile" src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=500&q=80" />
                    </div>
                    <div class="flex gap-3">
                      <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors">
                        <span class="material-symbols-outlined text-base">cloud_upload</span>
                        Upload new
                      </button>
                      <button class="text-sm font-semibold text-red-600 hover:text-red-700">Remove</button>
                    </div>
                  </div>
                  <p class="text-sm text-text-muted-light">Upload a square image for the best result.</p>
                </div>

                <div class="space-y-3">
                  <p class="text-sm font-semibold text-text-light">Or, choose one from the default images</p>
                  <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar1" checked />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border-2 border-primary bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Alex" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar2" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Sky" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar3" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Taylor" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar4" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Jordan" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar5" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Sam" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar6" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Jamie" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar7" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Riley" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="avatar8" />
                      <img alt="Avatar option" class="w-14 h-14 rounded-full border border-gray-200 bg-white object-cover"
                        src="https://api.dicebear.com/7.x/adventurer/svg?seed=Quinn" />
                      <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                  </div>
                </div>
              </div>

              <div class="border-t border-gray-100 pt-6">
                <div class="grid gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="new-password">Change password</label>
                    <input id="new-password" type="password" placeholder="Enter new password"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                  </div>
                  <div class="space-y-2">
                    <label class="text-sm font-semibold text-text-light" for="confirm-password">Verify password</label>
                    <input id="confirm-password" type="password" placeholder="Re-enter password"
                      class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                  </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                  <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors">
                    <span class="material-symbols-outlined text-base">save</span>
                    Save changes
                  </button>
                  <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                    <span class="material-symbols-outlined text-base">delete</span>
                    Delete account
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection
