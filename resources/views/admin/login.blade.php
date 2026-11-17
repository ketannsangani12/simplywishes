<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" type="image/png" href="{{ asset('images/simplywishes-icon.png') }}" />
  <title>Simply Wishes - Admin Log In</title>
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
</head>

<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans antialiased">
  <main class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="flex flex-col items-center mb-8">
        <img src="{{ asset('images/simplywishes-logo.png') }}" alt="Simply Wishes" class="w-48 h-auto" />
        <span class="mt-1 text-sm font-semibold uppercase tracking-[0.2em] text-text-muted-light dark:text-text-muted-dark">Admin Panel</span>
      </div>

      <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg border border-border-light dark:border-border-dark p-6 sm:p-8">
        <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark mb-2">Log In</h1>
        <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-6">Sign in with your administrator account to continue.</p>

        <form class="space-y-4" method="post" action="{{ route('admin.login.submit') }}">
          @csrf
          @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
              {{ session('status') }}
            </div>
          @endif
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
            <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
            @error('email')
              <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="password">Password</label>
            <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="password" name="password" type="password" required />
            @error('password')
              <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
          <div class="flex items-center gap-2 pt-1">
            <input checked id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
            <label class="text-sm font-semibold text-text-light dark:text-text-dark" for="remember">Remember Me</label>
          </div>
          <div class="pt-2">
            <button class="w-full px-5 py-2.5 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Log In</button>
          </div>
        </form>
      </div>

      <p class="mt-6 text-center text-sm text-text-muted-light dark:text-text-muted-dark">
        <a class="text-[#1f4e79] dark:text-brand-blue-dark font-medium hover:underline" href="{{ route('home') }}">&larr; Back to Simply Wishes</a>
      </p>
    </div>
  </main>
</body>

</html>
