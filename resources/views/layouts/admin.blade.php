<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Simply Wishes - Admin')</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
    rel="stylesheet" />
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#FFD700",
            "background-light": "#F8FAFC",
            "background-dark": "#0B1120",
            "surface-light": "#FFFFFF",
            "surface-dark": "#161E31",
            "text-light": "#0F172A",
            "text-dark": "#E2E8F0",
            "text-muted-light": "#64748B",
            "text-muted-dark": "#94A3B8",
            "brand-blue-light": "#083344",
            "brand-blue-dark": "#67E8F9",
            "border-light": "#E5E7EB",
            "border-dark": "#334155",
          },
          fontFamily: {
            display: ["Playfair Display", "serif"],
            sans: ["Poppins", "sans-serif"],
          },
          borderRadius: {
            DEFAULT: "0.5rem",
            lg: "0.75rem",
            xl: "1rem",
          },
        },
      },
    };
  </script>
  @stack('head')
</head>

<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans antialiased">
  <div class="flex flex-col min-h-screen">
    <header class="sticky top-0 z-50 bg-surface-light/80 dark:bg-surface-dark/80 backdrop-blur-sm shadow-sm">
      <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4 h-16">
          <a aria-label="Admin dashboard" class="flex items-center gap-3" href="{{ route('admin.dashboard') }}">
            <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <circle class="fill-current text-[#FBBF24]" cx="50" cy="50" r="5"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-80" cx="50" cy="30" r="4"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-70" cx="70" cy="40" r="3.5"></circle>
              <circle class="fill-current text-[#FBBF24] opacity-70" cx="30" cy="40" r="3.5"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-90" cx="80" cy="55" r="3"></circle>
              <circle class="fill-current text-[#38BDF8] opacity-90" cx="20" cy="55" r="3"></circle>
            </svg>
            <span class="text-xl font-bold font-display text-brand-blue-light dark:text-brand-blue-dark">Simply<span class="text-primary">Wishes</span></span>
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-text-muted-light dark:text-text-muted-dark border-l border-border-light dark:border-border-dark pl-3">Admin</span>
          </a>
          <div class="flex items-center gap-5">
            <a class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-[#1f70c1] dark:text-brand-blue-dark' : 'text-text-light dark:text-text-dark' }} hover:text-[#1f70c1] transition-colors" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="text-sm font-medium {{ request()->routeIs('admin.happy-stories.*') ? 'text-[#1f70c1] dark:text-brand-blue-dark' : 'text-text-light dark:text-text-dark' }} hover:text-[#1f70c1] transition-colors" href="{{ route('admin.happy-stories.index') }}">Happy Stories</a>
            <a class="text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'text-[#1f70c1] dark:text-brand-blue-dark' : 'text-text-light dark:text-text-dark' }} hover:text-[#1f70c1] transition-colors" href="{{ route('admin.reports.index') }}">Reports</a>
            <span class="hidden sm:block text-sm text-text-muted-light dark:text-text-muted-dark border-l border-border-light dark:border-border-dark pl-5">{{ auth()->user()->name }}</span>
            <form method="post" action="{{ route('admin.logout') }}">
              @csrf
              <button class="px-4 py-2 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Log Out</button>
            </form>
          </div>
        </div>
      </nav>
    </header>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10">
      @if (session('status'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
          {{ session('status') }}
        </div>
      @endif
      @yield('content')
    </main>
  </div>
</body>

</html>
