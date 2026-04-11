@extends('layouts.app', ['bodyClass' => 'bg-gradient-to-b from-background-light to-white dark:from-background-dark dark:to-surface-dark text-text-light dark:text-text-dark font-sans antialiased'])

@section('title', 'Simply Wishes - Reset Password')

@section('content')
<main class="flex-grow">
  <section class="relative overflow-hidden py-14 sm:py-16">
    <div
      class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(103,232,249,0.25),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(255,215,0,0.18),transparent_30%)] pointer-events-none">
    </div>
    <div class="container relative mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto grid gap-10 lg:grid-cols-[1.1fr_1fr] items-center">
        <div class="space-y-4">
          <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/70 dark:bg-white/10 text-sm font-semibold text-brand-blue-light dark:text-brand-blue-dark shadow-sm ring-1 ring-primary/30">
            <span class="material-symbols-outlined text-base">vpn_key</span>
            Set a new password
          </p>
          <h1 class="text-3xl sm:text-4xl font-display font-bold text-brand-blue-light dark:text-white leading-tight">
            Create your new password
          </h1>
          <p class="text-text-muted-light dark:text-text-muted-dark max-w-xl">
            Choose a strong password to secure your SimplyWishes account.
          </p>
        </div>

        <div class="relative">
          <div class="absolute -inset-2 bg-gradient-to-br from-primary/40 via-white to-brand-blue-dark/30 blur-xl rounded-3xl opacity-80"></div>
          <div class="relative bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-gray-700 shadow-glow p-6 sm:p-7 space-y-6">
            <div class="flex items-start gap-3">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-blue-light text-white shadow-md">
                <span class="material-symbols-outlined">lock_open</span>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">Reset password</h2>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Enter your email and a new password.</p>
              </div>
            </div>

            <form class="space-y-5" method="post" action="{{ route('password.update') }}">
              @csrf
              <input type="hidden" name="token" value="{{ $token }}">
              @if (session('status'))
                <div class="rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 text-sm">
                  {{ session('status') }}
                </div>
              @endif
              @if ($errors->any())
                <div class="rounded-lg bg-red-50 text-red-700 border border-red-200 px-4 py-3 text-sm">
                  Please check the form and try again.
                </div>
              @endif

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="email">Email</label>
                <input id="email" name="email" type="email" required
                  value="{{ old('email', $email) }}"
                  class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-text-muted-light dark:placeholder:text-text-muted-dark"
                  placeholder="you@example.com" />
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password">New password</label>
                <input id="password" name="password" type="password" required
                  class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-text-muted-light dark:placeholder:text-text-muted-dark"
                  placeholder="••••••••" />
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                  class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-text-light dark:text-text-dark px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-text-muted-light dark:placeholder:text-text-muted-dark"
                  placeholder="••••••••" />
              </div>

              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <button type="submit"
                  class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-brand-blue-light text-white font-semibold shadow-sm hover:opacity-90 transition-colors">
                  <span class="material-symbols-outlined text-base">check_circle</span>
                  Update password
                </button>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue-light hover:underline">
                  <span class="material-symbols-outlined text-base">arrow_back</span>
                  Back to log in
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
