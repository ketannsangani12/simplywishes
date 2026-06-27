@extends('layouts.app', ['bodyClass' => 'bg-background-light text-text-light font-sans antialiased', 'headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Update Profile')

@section('content')
@php
  $profileImage = $user?->profile_image
    ? (filter_var($user->profile_image, FILTER_VALIDATE_URL) ? $user->profile_image : asset($user->profile_image))
    : null;
  $currentFullName = trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? '')) ?: ($user?->name ?? 'Your profile');
  $defaultImages = [];
  $candidateDirectories = [
    public_path('images/users-default'),
    base_path('../public_html/images/users-default'),
  ];

  foreach ($candidateDirectories as $directory) {
    if (! is_dir($directory)) {
      continue;
    }

    $defaultImages = glob($directory . '/*') ?: [];
    if ($defaultImages !== []) {
      sort($defaultImages);
      break;
    }
  }
@endphp

<main class="flex-1">
  <section class="relative overflow-hidden py-10 sm:py-14">
    <div class="absolute inset-0 bg-gradient-to-br from-[#e0f2fe] via-white to-[#fef9c3] opacity-70"></div>
    <div class="container relative mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-6xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-glow">
        <div class="p-6 sm:p-8 border-b border-gray-100">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-sm font-semibold text-text-muted-light uppercase tracking-wide">Account settings</p>
              <h1 class="text-3xl font-display font-bold text-brand-blue-light">Update profile</h1>
              <p class="text-sm text-text-muted-light">Keep your details fresh so the community can reach you.</p>
            </div>
            <div class="text-sm text-text-muted-light">
              Logged in as <span class="font-semibold text-text-light">{{ $currentFullName }}</span>
            </div>
          </div>
        </div>

        <form class="p-6 sm:p-8 space-y-8" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-form">
          @csrf
          @method('PUT')

          @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
              <p class="font-semibold">Please fix the following:</p>
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-text-light" for="account-email">Email <span class="text-red-500">*</span></label>
              <input id="account-email" name="email" type="email" value="{{ old('email', $user?->email) }}"
                class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
              @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="first-name">First name <span class="text-red-500">*</span></label>
                <input id="first-name" name="first-name" type="text" value="{{ old('first-name', $user?->first_name) }}"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                @error('first-name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>
              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="last-name">Last name <span class="text-red-500">*</span></label>
                <input id="last-name" name="last-name" type="text" value="{{ old('last-name', $user?->last_name) }}"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                @error('last-name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>
            </div>

            <div class="md:col-span-2 space-y-2">
              <label class="text-sm font-semibold text-text-light" for="about">About me</label>
              <textarea id="about" name="about" rows="4"
                class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                placeholder="Tell the community a little about yourself">{{ old('about', $user?->about) }}</textarea>
              @error('about')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:col-span-2">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="country">Country <span class="text-red-500">*</span></label>
                <select id="country" name="country"
                  data-states-url="{{ route('states.by.country', ['country' => '__ID__']) }}"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                  <option value="">--Select Country--</option>
                  @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected((string) old('country', $selectedCountry?->id) === (string) $country->id)>{{ $country->name }}</option>
                  @endforeach
                </select>
                @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="state">State <span class="text-red-500">*</span></label>
                <select id="state" name="state" disabled data-cities-url="{{ route('cities.by.state', ['state' => '__ID__']) }}"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                  <option value="">--Select State--</option>
                </select>
                @error('state')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="city">City <span class="text-red-500">*</span></label>
                <select id="city" name="city" disabled
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                  <option value="">--Select City--</option>
                </select>
                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>

          <div class="grid lg:grid-cols-[280px_1fr] gap-6">
            <div class="space-y-4">
              <div class="space-y-2">
                <p class="text-sm font-semibold text-text-light">Profile image</p>
                <div class="w-52 h-52 rounded-2xl overflow-hidden border-2 border-primary/50 bg-gray-50 shadow-sm">
                  <img id="profile-preview" class="w-full h-full object-cover" alt="Current profile" src="{{ $profileImage ?: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=500&q=80' }}" />
                </div>
                <div class="flex flex-wrap gap-3">
                  <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-base">cloud_upload</span>
                    Upload new
                    <input class="hidden" type="file" name="avatar" accept="image/*" id="avatar-input" />
                  </label>
                  <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                    <input type="checkbox" name="remove_avatar" value="1" class="hidden" id="remove-avatar">
                    <span class="material-symbols-outlined text-base">delete</span>
                    Remove
                  </label>
                </div>
                @error('avatar')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
              </div>

              <p class="text-sm text-text-muted-light">Upload a square image for the best result.</p>
            </div>

            <div class="space-y-3">
              <p class="text-sm font-semibold text-text-light">Or, choose one from the default images</p>
              <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                @foreach ($defaultImages as $index => $path)
                  @php $fileName = basename($path); @endphp
                  <label class="relative group cursor-pointer">
                    <input class="peer sr-only" name="avatar-default" type="radio" value="{{ $fileName }}" @checked(($user?->profile_image === 'images/users-default/' . $fileName)) />
                    <img alt="Avatar option {{ $index + 1 }}" class="w-14 h-14 rounded-full border-2 border-gray-200 bg-white object-cover"
                      src="{{ asset('images/users-default/' . $fileName) }}" />
                    <span class="absolute inset-0 rounded-full ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-brand-blue-light hover:border-primary hover:text-primary transition-colors">
              <span class="material-symbols-outlined text-base">undo</span>
              Reset
            </a>
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors">
              <span class="material-symbols-outlined text-base">save</span>
              Save changes
            </button>
          </div>
        </form>

        <form class="px-6 pb-8 sm:px-8" method="POST" action="{{ route('profile.password.update') }}" id="password-form">
          @csrf
          @method('PUT')

          <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-sm">
            <div class="mb-4">
              <h2 class="text-lg font-semibold text-brand-blue-light">Change Password</h2>
              <p class="text-sm text-text-muted-light">Set a new password for your account.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="password">New password</label>
                <input id="password" name="password" type="password" placeholder="Enter new password"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>
              <div class="space-y-2">
                <label class="text-sm font-semibold text-text-light" for="confirm">Confirm password</label>
                <input id="confirm" name="confirm" type="password" placeholder="Re-enter password"
                  class="w-full rounded-lg border border-gray-200 bg-white text-text-light px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary" />
                @error('confirm')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>
            </div>
            <div class="mt-4 flex justify-end">
              <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold hover:opacity-90 transition-colors">
                <span class="material-symbols-outlined text-base">lock_reset</span>
                Update password
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const avatarInput = document.getElementById('avatar-input');
    const profilePreview = document.getElementById('profile-preview');
    const removeAvatar = document.getElementById('remove-avatar');
    const statesUrlTemplate = countrySelect.dataset.statesUrl;
    const citiesUrlTemplate = stateSelect.dataset.citiesUrl;
    let selectedStateId = @json((string) old('state', $selectedState?->id ?? ''));
    let selectedCityId = @json((string) old('city', $selectedCity?->id ?? ''));

    const resetSelect = (select, placeholder) => {
      select.innerHTML = `<option value="">${placeholder}</option>`;
    };

    const populate = (select, items, selectedId = '') => {
      items.forEach((item) => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.name;
        if (String(item.id) === String(selectedId)) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    };

    const loadStates = async (countryId) => {
      resetSelect(stateSelect, '--Select State--');
      resetSelect(citySelect, '--Select City--');
      stateSelect.disabled = true;
      citySelect.disabled = true;

      if (!countryId) {
        return;
      }

      const response = await fetch(statesUrlTemplate.replace('__ID__', countryId));
      const states = await response.json();
      populate(stateSelect, states, selectedStateId);
      stateSelect.disabled = false;

      if (selectedStateId) {
        await loadCities(selectedStateId);
      }
    };

    const loadCities = async (stateId) => {
      resetSelect(citySelect, '--Select City--');
      citySelect.disabled = true;

      if (!stateId) {
        return;
      }

      const response = await fetch(citiesUrlTemplate.replace('__ID__', stateId));
      const cities = await response.json();
      populate(citySelect, cities, selectedCityId);
      citySelect.disabled = false;
    };

    countrySelect.addEventListener('change', (event) => {
      selectedStateId = '';
      selectedCityId = '';
      loadStates(event.target.value);
    });

    stateSelect.addEventListener('change', (event) => {
      selectedCityId = '';
      loadCities(event.target.value);
    });

    avatarInput?.addEventListener('change', () => {
      if (avatarInput.files && avatarInput.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          profilePreview.src = e.target.result;
        };
        reader.readAsDataURL(avatarInput.files[0]);
        removeAvatar.checked = false;
      }
    });

    removeAvatar?.addEventListener('change', () => {
      if (removeAvatar.checked) {
        profilePreview.src = 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=500&q=80';
        avatarInput.value = '';
      }
    });

    if (countrySelect.value) {
      loadStates(countrySelect.value);
    }
  });
</script>
@endsection
