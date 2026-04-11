@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
  <main class="flex-grow">
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="max-w-md mx-auto bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
        <h1 class="text-2xl font-semibold text-text-light dark:text-text-dark">Verify your email</h1>
        <p class="mt-2 text-sm text-text-muted-light dark:text-text-muted-dark">
          We’ve sent a verification link to your email address. Please click the link to activate your account.
        </p>

        @if (session('status'))
          <div class="mt-4 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ session('status') }}
          </div>
        @endif

        <form class="mt-4" method="post" action="{{ route('verification.send') }}">
          @csrf
          <button class="w-full px-4 py-2.5 rounded-lg bg-primary text-brand-blue-light font-semibold hover:bg-primary/90 transition-colors" type="submit">
            Resend verification email
          </button>
        </form>
      </div>
    </section>
  </main>
@endsection
