@extends('layouts.app')

@section('title', 'Simply Wishes - Log In')

@section('content')
<main class="flex-grow">
      <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md mx-auto">
          <h1 class="text-2xl font-semibold text-[#1f4e79] mb-3">Log In</h1>
          <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-6">Please fill out the following fields to login:</p>
          <form class="space-y-4" method="post" action="{{ route('login.submit') }}">
            @csrf
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
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="email">Email</label>
              <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="email" name="email" type="email" value="{{ old('email') }}" />
              @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="space-y-1">
              <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password">Password</label>
              <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="password" name="password" type="password" />
              @error('password')
                <p class="text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex items-center gap-2 pt-1">
              <input checked id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
              <label class="text-sm font-semibold text-text-light dark:text-text-dark" for="remember">Remember Me</label>
            </div>
            <div class="text-sm text-text-muted-light dark:text-text-muted-dark">
              Forgotten Password? reset it <a class="text-[#1f4e79] font-medium hover:underline" href="{{ route('password.forgot') }}">here</a>.
            </div>
            <div class="flex items-center gap-3 pt-2">
              <button class="px-5 py-2.5 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Log In</button>
              <a class="px-5 py-2.5 bg-[#4caf50] text-white text-sm font-semibold rounded-md hover:bg-[#429b46] transition-colors inline-block text-center" href="{{ route('signup') }}">Sign Up</a>
            </div>
            <div class="pt-2">
              <a class="w-full inline-flex items-center justify-center gap-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/60 rounded-md px-4 py-2.5 text-sm font-semibold text-text-light dark:text-text-dark hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                href="{{ route('auth.google.redirect') }}">
                <img alt="Google" class="h-5 w-5" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" />
                <span>Sign in with Google</span>
              </a>
            </div>
          </form>
        </div>
      </section>
    </main>
@endsection
