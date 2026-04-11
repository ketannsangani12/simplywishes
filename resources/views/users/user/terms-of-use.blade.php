@extends('layouts.app', ['headerPartial' => 'partials.header-public'])

@section('title', 'Terms of Use')

@section('content')
<main class="flex-1 bg-background-light dark:bg-background-dark">
  <section class="relative overflow-hidden bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="max-w-4xl">
        <p class="text-sm uppercase tracking-[0.3em] text-text-muted-light dark:text-text-muted-dark">Simply Wishes</p>
        <h1 class="mt-3 text-3xl md:text-4xl font-semibold text-brand-blue-light dark:text-brand-blue-dark">Terms of Use</h1>
        <p class="mt-4 text-lg text-text-light dark:text-text-dark leading-relaxed">
          Welcome to the SimplyWishes Website. By accessing and using this website, you are agreeing to these Terms of Use
          ("Terms of Use"). Please read them carefully.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <article class="max-w-4xl mx-auto bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 sm:p-8 space-y-10">
      <div class="space-y-4">
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          These Terms of Use were last updated in 2023.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">1. Acceptance of Terms of Use; Modifications.</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          SimplyWishes, Inc. ("SimplyWishes" "we" or "us" or "our") owns and operates the website,
          <a class="text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="https://www.simplywishes.com">www.simplywishes.com</a>,
          including, but not limited to, all mobile and touch applications and platforms, electronic services, social
          networking sites, interactive features, online services, and any of the described online activities SimplyWishes
          owns or controls either now or in the future (collectively, "Site"). By using the Site and the services available
          via the Site, you agree to these Terms of Use and any additional terms applicable to certain programs in which you
          may elect to participate. You also agree to the SimplyWishes Privacy Policy, located at
          <a class="text-brand-blue-light dark:text-brand-blue-dark hover:underline" href="https://www.simplywishes.com/privacy">www.simplywishes.com/privacy</a>,
          which is incorporated herein by reference and any reference to these Terms of Use herein shall be deemed to
          reference and include the Privacy Policy. The term "using" includes any person or entity that accesses or uses the
          Site with crawlers, robots, data mining or extraction tools or any other functionality.
        </p>
        <p class="text-text-light dark:text-text-dark leading-relaxed font-semibold">
          IF YOU DO NOT AGREE TO THESE TERMS OF USE, YOU MAY NOT USE OR ACCESS THE SITE. YOU SHOULD IMMEDIATELY STOP USING THE
          SITE AND NOT PARTICIPATE IN ANY PROGRAM, GOODS OR SERVICES OFFERED THROUGH THE SITE.
        </p>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          SimplyWishes reserves the right at all times to discontinue or modify any part of these Terms of Use in its sole
          discretion. If SimplyWishes make changes that affect your use of the Site or the services offered by SimplyWishes,
          SimplyWishes will post notice of the change on the Terms of Use page. Any changes to these Terms of Use will be
          effective upon the posting by SimplyWishes of the notice. If you do not agree to the changes, you must discontinue
          use of the Site and any services or goods offered via the Site after the effective date of the changes.
          SimplyWishes suggests that you revisit these Terms of Use regularly to ensure that you stay informed of any changes.
          You agree that posting notice of any changes on the Terms of Use page is adequate notice to advise you of these
          changes, and that your continued use of the Site and any services or goods provided via the Site constitutes
          acceptance of these changes and the Terms of Use as modified.
        </p>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          "SIMPLYWISHES OFFERS INFORMATION AND A METHOD FOR PERSONS WITH DONATIONS ("DONORS") TO CONNECT AND COORDINATE WITH
          PERSONS WHO CAN ACCEPT THOSE DONATIONS ("USERS"). EACH DONOR IS SOLELY RESPONSIBLE FOR VERIFYING THE LEGITIMACY OF ANY
          DONATION BEFORE DONATING IT. EACH USER IS SOLELY RESPONSIBLE FOR DETERMINING WHETHER TO ACCEPT A DONATION FROM A
          DONOR AND WHETHER SUCH DONATION WILL MEET THE USER'S NEEDS AND EXPECTATIONS. EACH DONOR AND USER ARE SOLELY
          RESPONSIBLE FOR CONFIRMING THE TERMS AND CONDITIONS UNDER WHICH A DONATION WILL BE FULFILLED, INCLUDING THE
          IDENTITY, CONTACT INFORMATION, AND OTHER PERTINENT INFORMATION REQUIRED TO DONATE THE ITEM. SIMPLYWISHES HAS NO
          RESPONSIBILITY OR LIABILITY FOR ANY DONATIONS SHARED OR FULFILLED ON THE SITE VIA THE SITE AND SIMPLYWISHES DOES NOT
          GUARANTEE THAT THE FULFILLMENT OF ANY DONATION WILL SATISFY THE NEEDS AND EXPECTATIONS OF THE USER."
        </p>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          SIMPLYWISHES IS NOT A PARTY TO ANY AGREEMENTS ENTERED BY USERS FOR THE FULFILLMENT OF DONATIONS. SIMPLY WISHES HAS
          NO CONTROL OVER THE CONDUCT OF USERS AND DONORS AND DISCLAIMS ALL LIABILITY IN THIS REGARD TO THE MAXIMUM EXTENT
          PERMITTED BY LAW. ANY USERS AND DONORS WHO DECIDE TO MEET IN PERSON REGARDING THE FULFILLMENT OF A DONATION ARE
          ENCOURAGED TO DO SO IN A SAFE MANNER FOR BOTH USERS.
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-text-light dark:text-text-dark">2. About the Site.</h2>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          The Site is a platform that connects persons with certain needs, wishes, and desires (a "Wish" or "Wishes") with
          persons who have the ability to fulfill such Wishes. Persons using the Site are referred to herein as "Users".
          SimplyWishes and the Site act only as a conduit or platform to provide information and connect Users. If any User
          determines in their sole discretion to grant the Wish of another User and/or donate an item to another User, such
          transaction will be solely between the two Users and neither SimplyWishes nor the Site will be a party to such
          transaction and neither has any responsibility or liability for any Wishes granted or items Donated by one User to
          another User.
        </p>
        <p class="text-text-light dark:text-text-dark leading-relaxed">
          SimplyWishes reserves the right to change or discontinue any aspect or feature of the Site or its services at any
          time with or without notice, including, but not limited to, requirements or eligibility to use the Site.
        </p>
      </div>
    </article>
  </section>
</main>
@endsection
