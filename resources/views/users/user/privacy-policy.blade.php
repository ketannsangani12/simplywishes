@extends('layouts.app', ['headerPartial' => 'partials.header-public'])

@section('title', 'Privacy Policy')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="relative overflow-hidden bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="max-w-4xl">
        <p class="text-sm uppercase tracking-[0.3em] text-text-muted-light dark:text-text-muted-dark">Simply Wishes</p>
        <h1 class="mt-3 text-3xl md:text-4xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Privacy Policy</h1>
        <p class="mt-4 text-lg text-text-light dark:text-text-dark leading-relaxed">
          This Privacy Policy explains how SimplyWishes, Inc. ("SimplyWishes," "we," "us," or "our") collects, uses,
          discloses, and protects information when you visit our websites, use our services, or interact with our
          communications.
        </p>
        <p class="mt-4 text-sm text-text-muted-light dark:text-text-muted-dark">Last updated: December 20, 2024</p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <article class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-10">
      <div class="space-y-3">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">1. Acceptance of Terms</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          By using SimplyWishes, you agree to this Privacy Policy and the Terms of Use. If you do not agree, please do not
          use the site or services.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">2. Information We Collect</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          We collect information you provide directly and information that is collected automatically when you use the
          site.
        </p>
        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Information you provide</h3>
          <ul class="list-disc pl-5 space-y-2 text-text-light dark:text-text-dark">
            <li>Account details such as name, email address, username, and profile photo.</li>
            <li>Content you post, such as wishes, donations, comments, and messages.</li>
            <li>Payment or transaction details when you donate, including billing address and transaction metadata.</li>
            <li>Support requests or other communications with our team.</li>
          </ul>
        </div>
        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Information collected automatically</h3>
          <ul class="list-disc pl-5 space-y-2 text-text-light dark:text-text-dark">
            <li>Device identifiers, IP address, browser type, and operating system.</li>
            <li>Usage data such as pages viewed, links clicked, and time spent on the site.</li>
            <li>Location data derived from IP address or device settings when available.</li>
          </ul>
        </div>
        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-brand-blue-light dark:text-brand-blue-dark">Cookies and similar technologies</h3>
          <p class="text-text-light dark:text-text-dark leading-relaxed">
            We use cookies and similar technologies to keep you signed in, remember preferences, analyze traffic, and improve
            the site. You can control cookies through your browser settings.
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">3. How We Use Information</h2>
        <ul class="list-disc pl-5 space-y-2 text-text-light dark:text-text-dark">
          <li>Provide, operate, and improve SimplyWishes features and community experiences.</li>
          <li>Process donations and support transactions securely.</li>
          <li>Personalize content, recommendations, and wish suggestions.</li>
          <li>Communicate with you about updates, support, and policy changes.</li>
          <li>Protect the safety and integrity of the community.</li>
        </ul>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">4. How We Share Information</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          We do not sell your personal information. We may share information in the following circumstances:
        </p>
        <ul class="list-disc pl-5 space-y-2 text-text-light dark:text-text-dark">
          <li>With service providers who help us operate the site (hosting, analytics, payments, and support).</li>
          <li>To comply with legal obligations, enforce policies, or protect rights and safety.</li>
          <li>In connection with a business transfer such as a merger or acquisition.</li>
          <li>With your consent or at your direction.</li>
        </ul>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">5. Your Choices</h2>
        <ul class="list-disc pl-5 space-y-2 text-text-light dark:text-text-dark">
          <li>Access and update your profile information from your account settings.</li>
          <li>Opt out of marketing emails by using the unsubscribe link in our messages.</li>
          <li>Disable cookies through your browser settings, noting some features may not work properly.</li>
        </ul>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">6. Data Retention</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          We retain information for as long as necessary to provide services, comply with legal obligations, resolve disputes,
          and enforce agreements. If you close your account, some information may remain in backups for a limited period.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">7. Security</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          We use reasonable administrative, technical, and physical safeguards designed to protect your information. No method
          of transmission or storage is completely secure, so we cannot guarantee absolute security.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">8. International Transfers</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          If you access SimplyWishes outside the United States, you acknowledge that your information may be transferred to
          and processed in the United States and other countries.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">9. Children's Privacy</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          SimplyWishes is not intended for children under 13, and we do not knowingly collect personal information from
          children. If we learn that we have collected such information, we will take appropriate steps to delete it.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">10. California Privacy Rights</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          California residents may have additional rights regarding personal information, including the right to request
          access, deletion, or correction of their information. To make a request, contact us using the information below.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">11. Changes to This Policy</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          We may update this Privacy Policy periodically. We will post the updated policy here with a new effective date.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">12. Contact Us</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          If you have questions about this Privacy Policy or our practices, contact us at
          <a class="text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="mailto:support@simplywishes.com">support@simplywishes.com</a>.
        </p>
      </div>
    </article>
  </section>
</main>
@endsection
