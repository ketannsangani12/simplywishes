@extends('layouts.app')

@section('title', 'Simply Wishes - Sign Up')

@section('content')
<main class="flex-grow">
      <section class="py-12 sm:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
          <div class="mb-8">
            <h1 class="text-3xl font-display font-bold text-brand-blue-light dark:text-white">Sign Up</h1>
            <p class="mt-2 text-text-muted-light dark:text-text-muted-dark">Create your account to start wishing,
              donating, and sharing stories.</p>
          </div>
          <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
            <form class="space-y-6" method="post" action="{{ route('signup.submit') }}" id="signup-form" novalidate>
              @csrf
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
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="email">Email
                    Address <span class="text-red-500">*</span></label>
                  <input id="email" name="email" required
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="you@example.com" type="email" />
                  @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="first-name">First
                    Name <span class="text-red-500">*</span></label>
                  <input id="first-name" name="first-name" required
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                    type="text" />
                  @error('first-name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="last-name">Last
                    Name <span class="text-red-500">*</span></label>
                  <input id="last-name" name="last-name" required
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                    type="text" />
                  @error('last-name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="about">About me</label>
                  <textarea id="about" name="about" rows="3"
                    class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Tell us a bit about yourself"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:col-span-2">
                  <div>
                    <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="country">Country
                      <span class="text-red-500">*</span></label>
                    <select id="country" name="country" required data-states-url="{{ route('states.by.country', ['country' => '__ID__']) }}"
                      class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option value="">--Select Country--</option>
                      @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                      @endforeach
                    </select>
                    @error('country')
                      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="state">State
                      <span class="text-red-500">*</span></label>
                    <select id="state" name="state" required disabled data-cities-url="{{ route('cities.by.state', ['state' => '__ID__']) }}"
                      class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option value="">--Select State--</option>
                    </select>
                    @error('state')
                      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="city">City
                      <span class="text-red-500">*</span></label>
                    <select id="city" name="city" required disabled
                      class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary">
                      <option value="">--Select City--</option>
                    </select>
                    @error('city')
                      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="password">Password
                    <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <input id="password" name="password" required
                      class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 pr-12 focus:ring-2 focus:ring-primary focus:border-primary"
                      type="password" />
                    <button type="button" aria-label="Show password"
                      class="absolute inset-y-0 right-3 flex items-center text-text-muted-light dark:text-text-muted-dark hover:text-primary"
                      data-toggle-password="password">
                      <span class="material-symbols-outlined text-xl" aria-hidden="true" data-icon>visibility</span>
                    </button>
                  </div>
                  @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label class="block text-sm font-semibold text-text-light dark:text-text-dark mb-2" for="confirm">Verify
                    Password <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <input id="confirm" name="confirm" required
                      class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 pr-12 focus:ring-2 focus:ring-primary focus:border-primary"
                      type="password" />
                    <button type="button" aria-label="Show password"
                      class="absolute inset-y-0 right-3 flex items-center text-text-muted-light dark:text-text-muted-dark hover:text-primary"
                      data-toggle-password="confirm">
                      <span class="material-symbols-outlined text-xl" aria-hidden="true" data-icon>visibility</span>
                    </button>
                  </div>
                  @error('confirm')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="space-y-3">
                <p class="text-sm font-semibold text-text-light dark:text-text-dark">Profile Image</p>
                <label
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white dark:bg-brand-blue-dark cursor-pointer hover:opacity-90">
                  <span class="material-icons text-base">cloud_upload</span>
                  <span>Choose an image from your files</span>
                  <input class="hidden" type="file" name="avatar" accept="image/*" />
                </label>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Or, choose one of the default images
                  below</p>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                  @php
                    $defaultImages = glob(public_path('images/users-default/*'));
                    sort($defaultImages);
                  @endphp
                  @foreach ($defaultImages as $index => $path)
                    @php
                      $fileName = basename($path);
                    @endphp
                    <label class="relative group cursor-pointer">
                      <input class="peer sr-only" name="avatar-default" type="radio" value="{{ $fileName }}"
                        @if ($index === 0) checked @endif />
                      <img alt="Avatar option {{ $index + 1 }}"
                        class="w-16 h-16 rounded-lg border border-gray-200 bg-white object-cover"
                        src="{{ asset('images/users-default/' . $fileName) }}" />
                      <span
                        class="absolute inset-0 rounded-lg ring-2 ring-primary/70 opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                    </label>
                  @endforeach
                </div>
              </div>

              <div class="flex items-start gap-3">
                <input class="mt-1 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary" id="terms" type="checkbox" required />
                <label class="text-sm text-text-light dark:text-text-dark" for="terms">I agree to the <a class="text-brand-blue-light hover:underline" href="{{ route('terms.of.use') }}">Terms Of Use</a>, <a class="text-brand-blue-light hover:underline" href="#">Community Guidelines</a> and <a class="text-brand-blue-light hover:underline" href="{{ route('privacy.policy') }}">Privacy Policy</a> <span class="text-red-500">*</span></label>
              </div>

              <div class="pt-2">
                <button
                  class="px-6 py-3 rounded-lg bg-primary text-brand-blue-light font-semibold hover:bg-primary/90 transition-colors shadow-sm"
                  type="submit">Sign Up</button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('signup-form');
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');
        const emailInput = document.getElementById('email');
        const firstNameInput = document.getElementById('first-name');
        const lastNameInput = document.getElementById('last-name');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm');
        const statesUrlTemplate = countrySelect.dataset.statesUrl;
        const citiesUrlTemplate = stateSelect.dataset.citiesUrl;

        const fieldErrors = new Map();
        const getErrorNode = (field) => {
          if (fieldErrors.has(field)) {
            return fieldErrors.get(field);
          }
          const error = document.createElement('p');
          error.className = 'mt-1 text-sm text-red-600';
          error.setAttribute('data-js-error', '');
          field.insertAdjacentElement('afterend', error);
          fieldErrors.set(field, error);
          return error;
        };

        const setError = (field, message) => {
          const errorNode = getErrorNode(field);
          errorNode.textContent = message;
          field.classList.add('border-red-400', 'focus:ring-red-200', 'focus:border-red-400');
        };

        const clearError = (field) => {
          const errorNode = fieldErrors.get(field);
          if (errorNode) {
            errorNode.textContent = '';
          }
          field.classList.remove('border-red-400', 'focus:ring-red-200', 'focus:border-red-400');
        };

        const isEmailValid = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

        const resetSelect = (select, placeholder) => {
          select.innerHTML = `<option value="">${placeholder}</option>`;
        };

        const buildUrl = (template, id) => template.replace('__ID__', id);

        const populateOptions = (select, items) => {
          items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            select.appendChild(option);
          });
        };

        countrySelect.addEventListener('change', () => {
          const countryId = countrySelect.value;
          resetSelect(stateSelect, '--Select State--');
          resetSelect(citySelect, '--Select City--');
          stateSelect.disabled = true;
          citySelect.disabled = true;

          if (!countryId) {
            return;
          }

          fetch(buildUrl(statesUrlTemplate, countryId))
            .then((response) => response.json())
            .then((states) => {
              resetSelect(stateSelect, '--Select State--');
              populateOptions(stateSelect, states);
              stateSelect.disabled = states.length === 0;
            })
            .catch(() => {
              resetSelect(stateSelect, '--Select State--');
              stateSelect.disabled = true;
            });
        });

        stateSelect.addEventListener('change', () => {
          const stateId = stateSelect.value;
          resetSelect(citySelect, '--Select City--');
          citySelect.disabled = true;

          if (!stateId) {
            return;
          }

          fetch(buildUrl(citiesUrlTemplate, stateId))
            .then((response) => response.json())
            .then((cities) => {
              resetSelect(citySelect, '--Select City--');
              populateOptions(citySelect, cities);
              citySelect.disabled = cities.length === 0;
            })
            .catch(() => {
              resetSelect(citySelect, '--Select City--');
              citySelect.disabled = true;
            });
        });

        const validateField = (field) => {
          const value = field.value.trim();
          if (!value) {
            setError(field, 'This field is required.');
            return false;
          }
          if (field === emailInput && !isEmailValid(value)) {
            setError(field, 'Please enter a valid email address.');
            return false;
          }
          if (field === confirmInput && value !== passwordInput.value.trim()) {
            setError(field, 'Passwords do not match.');
            return false;
          }
          clearError(field);
          return true;
        };

        const validateSelect = (field, message) => {
          if (!field.value) {
            setError(field, message);
            return false;
          }
          clearError(field);
          return true;
        };

        [emailInput, firstNameInput, lastNameInput, passwordInput, confirmInput].forEach((field) => {
          field.addEventListener('blur', () => validateField(field));
          field.addEventListener('input', () => {
            if (fieldErrors.get(field)?.textContent) {
              validateField(field);
            }
          });
        });

        [countrySelect, stateSelect, citySelect].forEach((field) => {
          field.addEventListener('change', () => validateSelect(field, 'Please make a selection.'));
        });

        form.addEventListener('submit', (event) => {
          let isValid = true;
          let firstInvalidField = null;

          const setFirstInvalid = (field, valid) => {
            if (!valid && !firstInvalidField) {
              firstInvalidField = field;
            }
            return valid;
          };

          isValid = setFirstInvalid(emailInput, validateField(emailInput)) && isValid;
          isValid = setFirstInvalid(firstNameInput, validateField(firstNameInput)) && isValid;
          isValid = setFirstInvalid(lastNameInput, validateField(lastNameInput)) && isValid;
          isValid = setFirstInvalid(countrySelect, validateSelect(countrySelect, 'Please select a country.')) && isValid;
          isValid = setFirstInvalid(stateSelect, validateSelect(stateSelect, 'Please select a state.')) && isValid;
          isValid = setFirstInvalid(citySelect, validateSelect(citySelect, 'Please select a city.')) && isValid;
          isValid = setFirstInvalid(passwordInput, validateField(passwordInput)) && isValid;
          isValid = setFirstInvalid(confirmInput, validateField(confirmInput)) && isValid;

          if (!isValid) {
            event.preventDefault();
            if (firstInvalidField) {
              firstInvalidField.focus({ preventScroll: true });
              firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
          }
        });
      });
    </script>
@endsection
