@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin - Change Password')

@section('content')
<div class="max-w-md">
  <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark mb-3">Change Password</h1>
  <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-6">Update the password for your admin account ({{ auth()->user()->email }}).</p>

  <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 sm:p-8">
    <form class="space-y-4" method="post" action="{{ route('admin.password.update') }}">
      @csrf
      @method('PUT')

      @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
          <p class="font-semibold">Please fix the following:</p>
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="space-y-1">
        <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="current_password">Current Password</label>
        <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="current_password" name="current_password" type="password" required autocomplete="current-password" />
        @error('current_password')
          <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="space-y-1">
        <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password">New Password</label>
        <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="password" name="password" type="password" required autocomplete="new-password" />
        @error('password')
          <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="space-y-1">
        <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password_confirmation">Confirm New Password</label>
        <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
      </div>

      <div class="pt-2">
        <button class="px-5 py-2.5 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Change Password</button>
      </div>
    </form>
  </div>
</div>
@endsection
