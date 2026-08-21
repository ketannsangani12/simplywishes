@extends('layouts.app', ['headerPartial' => 'partials.header-public'])

@section('title', 'Community Guidelines')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="relative overflow-hidden bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="max-w-4xl">
        <p class="text-sm uppercase tracking-[0.3em] text-text-muted-light dark:text-text-muted-dark">Simply Wishes</p>
        <h1 class="mt-3 text-3xl md:text-4xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Community Guidelines</h1>
        <p class="mt-4 text-lg text-text-light dark:text-text-dark leading-relaxed">
          SimplyWishes only works because wishers, granters, donors, and friends treat each other with honesty and
          respect. These guidelines describe what we expect from everyone in the community.
        </p>
        <p class="mt-4 text-sm text-text-muted-light dark:text-text-muted-dark">Last updated: August 21, 2026</p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <article class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-10">
      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">1. Be Honest</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Wishes, donations, happy stories, and profile details should reflect real needs and real experiences.
          Do not misrepresent who you are, exaggerate a need, or fabricate a story to gain sympathy, donations, or
          attention. Dishonest listings put resources meant for genuine wishes at risk and will be removed.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">2. Be Respectful</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Treat other members the way you'd want to be treated. Harassment, hate speech, threats, bullying, sexual
          content, or discrimination based on race, religion, gender, disability, or any other protected
          characteristic is never allowed in wishes, donations, forum posts, comments, chat messages, or profiles.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">3. Keep It Safe</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Don't share other people's private information without their consent, and be thoughtful about your own.
          When meeting to hand off a donation, choose a public place and bring someone with you when possible. Never
          send money to a stranger outside of the payment methods a listing already provides.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">4. No Spam or Scams</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Do not use wishes, donations, the forum, or chat to advertise unrelated products or services, run
          fundraising schemes for causes outside SimplyWishes, or attempt to solicit payments outside the platform's
          intended flow. Accounts used primarily to spam or scam other members will be suspended.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">5. Respect Content Ownership</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          Only upload photos and videos you own or have permission to use. Do not post copyrighted material,
          including images of characters, brands, or products you don't have the rights to share.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">6. Reporting and Enforcement</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          If you come across a wish, donation, forum post, happy story, comment, or message that breaks these
          guidelines, use the Report option available on that content so our team can review it. You can also block
          a member from your profile page if they've been contacting you inappropriately. Reports are reviewed by our
          team, and confirmed violations may result in content removal, a warning, or account suspension depending on
          severity.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">7. Questions</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          If you're unsure whether something fits these guidelines, or you'd like to appeal a decision, contact us at
          <a class="text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="mailto:support@simplywishes.com">support@simplywishes.com</a>.
        </p>
      </div>
    </article>
  </section>
</main>
@endsection
