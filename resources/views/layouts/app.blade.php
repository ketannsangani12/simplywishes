<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Simply Wishes')</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
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
            "card-light": "#FFFFFF",
            "card-dark": "#161E31",
            "subtle-light": "#64748B",
            "subtle-dark": "#94A3B8",
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
  <style>
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
    }

    .material-icons {
      font-size: 20px;
      vertical-align: middle;
    }

    /* Keep horizontal scrollers tidy on mobile */
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
  </style>
  @stack('head')
</head>

<body class="{{ $bodyClass ?? 'bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans antialiased' }}">
  <div class="flex flex-col min-h-screen" id="root">
    @php
      $headerPartial = $headerPartial ?? (auth()->check() ? 'partials.header-auth' : 'partials.header-public');
    @endphp
    @include($headerPartial)
    @yield('content')
    @include('partials.footer')
  </div>
</body>

</html>
