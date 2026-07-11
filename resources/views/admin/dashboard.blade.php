@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin Dashboard')

@section('content')
<h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark mb-3">Dashboard</h1>
<p class="text-sm text-text-muted-light dark:text-text-muted-dark">Welcome to the Simply Wishes admin panel.</p>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-8">
  <a class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 hover:shadow-md transition-shadow" href="{{ route('admin.happy-stories.index') }}">
    <p class="text-sm font-semibold text-text-muted-light dark:text-text-muted-dark uppercase tracking-wide">Happy Stories</p>
    <p class="mt-2 text-3xl font-bold text-[#1f4e79] dark:text-brand-blue-dark">{{ \App\Models\HappyStory::count() }}</p>
    <p class="mt-1 text-xs text-text-muted-light dark:text-text-muted-dark">{{ \App\Models\HappyStory::where('status', 1)->count() }} active</p>
  </a>
  <a class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 hover:shadow-md transition-shadow" href="{{ route('admin.reports.index') }}">
    <p class="text-sm font-semibold text-text-muted-light dark:text-text-muted-dark uppercase tracking-wide">Reported Content</p>
    <p class="mt-2 text-3xl font-bold text-[#1f4e79] dark:text-brand-blue-dark">{{ \App\Models\Report::where('status', 0)->count() }}</p>
    <p class="mt-1 text-xs text-text-muted-light dark:text-text-muted-dark">pending reports</p>
  </a>
</div>
@endsection
