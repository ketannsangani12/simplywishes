@extends('layouts.app', ['headerPartial' => 'partials.header-public'])

@section('title', 'Contact Us')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="relative overflow-hidden bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="max-w-4xl">
        <p class="text-sm uppercase tracking-[0.3em] text-text-muted-light dark:text-text-muted-dark">Simply Wishes</p>
        <h1 class="mt-3 text-3xl md:text-4xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Contact Us</h1>
        <p class="mt-4 text-lg text-text-light dark:text-text-dark leading-relaxed">
          Have a question, need help with your account, or want to flag something that doesn't feel right? We're
          glad to hear from you.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <div class="max-w-4xl mx-auto grid gap-6 sm:grid-cols-2">
      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-3">
        <span class="material-symbols-outlined text-3xl text-brand-blue-light dark:text-brand-blue-dark">mail</span>
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">Email Support</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          For account help, questions about a wish or donation, or anything else, email us and we'll get back to you
          as soon as we can.
        </p>
        <a class="inline-flex items-center gap-2 text-brand-blue-light dark:text-brand-blue-dark font-semibold hover:underline"
          href="mailto:support@simplywishes.com">
          <span class="material-symbols-outlined text-base">forward_to_inbox</span> support@simplywishes.com
        </a>
      </div>

      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-3">
        <span class="material-symbols-outlined text-3xl text-brand-blue-light dark:text-brand-blue-dark">flag</span>
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">Reporting Content or a Member</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Spotted a wish, donation, forum post, story, comment, or message that breaks our
          <a class="text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="{{ route('community.guidelines') }}">Community Guidelines</a>?
          Use the Report option directly on that content so our team can review it, or block a member from their
          profile page if they've contacted you inappropriately.
        </p>
      </div>

      <div class="bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-3 sm:col-span-2">
        <span class="material-symbols-outlined text-3xl text-brand-blue-light dark:text-brand-blue-dark">groups</span>
        <h2 class="text-xl font-semibold text-text-light dark:text-text-dark">Follow Along</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          You can also find us on social media — links are in the footer at the bottom of every page.
        </p>
      </div>
    </div>
  </section>
</main>
@endsection
