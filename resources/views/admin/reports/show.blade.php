@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin - Report #' . $report->id)

@section('content')
<div class="max-w-3xl">
  <div class="mb-6">
    <a class="text-sm text-[#1f4e79] dark:text-brand-blue-dark font-medium hover:underline" href="{{ route('admin.reports.index') }}">&larr; Back to Reported Content</a>
    <div class="flex flex-wrap items-center gap-3 mt-2">
      <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark">Report #{{ $report->id }}</h1>
      @if ((int) $report->status === 0)
        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Pending</span>
      @else
        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Resolved{{ $report->resolved_at ? ' on ' . \Illuminate\Support\Carbon::parse($report->resolved_at)->format('M d, Y') : '' }}</span>
      @endif
    </div>
  </div>

  <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 mb-6">
    <h2 class="text-sm font-semibold uppercase tracking-wide text-text-muted-light dark:text-text-muted-dark mb-4">Report Details</h2>
    <dl class="grid gap-4 sm:grid-cols-2 text-sm">
      <div>
        <dt class="font-semibold text-text-muted-light dark:text-text-muted-dark">Content Type</dt>
        <dd class="mt-1">{{ $resolved['label'] }}</dd>
      </div>
      <div>
        <dt class="font-semibold text-text-muted-light dark:text-text-muted-dark">Reported On</dt>
        <dd class="mt-1">{{ $report->created_at ? \Illuminate\Support\Carbon::parse($report->created_at)->format('M d, Y H:i') : '—' }}</dd>
      </div>
      <div>
        <dt class="font-semibold text-text-muted-light dark:text-text-muted-dark">Reported By</dt>
        <dd class="mt-1">{{ $report->reporter?->name ?? '—' }}<span class="block text-xs text-text-muted-light dark:text-text-muted-dark">{{ $report->reporter?->email }}</span></dd>
      </div>
      <div>
        <dt class="font-semibold text-text-muted-light dark:text-text-muted-dark">Reported User</dt>
        <dd class="mt-1">{{ $report->reportedUser?->name ?? '—' }}<span class="block text-xs text-text-muted-light dark:text-text-muted-dark">{{ $report->reportedUser?->email }}</span></dd>
      </div>
      <div class="sm:col-span-2">
        <dt class="font-semibold text-text-muted-light dark:text-text-muted-dark">Reason / Details</dt>
        <dd class="mt-1">{{ $report->reason }}{{ $report->details ? ' — ' . $report->details : '' }}</dd>
      </div>
    </dl>
  </div>

  <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 mb-6">
    <div class="flex items-center justify-between gap-4 mb-4">
      <h2 class="text-sm font-semibold uppercase tracking-wide text-text-muted-light dark:text-text-muted-dark">Reported Content</h2>
      @if ($resolved['siteUrl'])
        <a class="text-sm text-[#1f4e79] dark:text-brand-blue-dark font-medium hover:underline" href="{{ $resolved['siteUrl'] }}" target="_blank" rel="noopener">View on site &nearr;</a>
      @endif
    </div>

    @if ($resolved['model'])
      @if ($resolved['title'])
        <h3 class="text-lg font-semibold text-text-light dark:text-text-dark mb-2">{{ $resolved['title'] }}</h3>
      @endif
      @if ($resolved['image'])
        <img alt="Reported content image" class="max-h-64 rounded-md object-cover border border-border-light dark:border-border-dark mb-3" src="{{ asset($resolved['image']) }}" />
      @endif
      <p class="text-sm whitespace-pre-line">{{ $resolved['text'] }}</p>
      @if ($resolved['author'])
        <p class="mt-4 text-xs text-text-muted-light dark:text-text-muted-dark">Posted by {{ $resolved['author']->name }} ({{ $resolved['author']->email }})</p>
      @endif
    @else
      <p class="text-sm italic text-text-muted-light dark:text-text-muted-dark">The reported content has been deleted or could not be resolved.</p>
    @endif
  </div>

  @if ((int) $report->status === 0)
    <div class="flex items-center gap-3">
      @if ($resolved['model'])
        <form method="post" action="{{ route('admin.reports.content.destroy', $report) }}" onsubmit="return confirm('Delete this {{ strtolower($resolved['label']) }} and all its related data? This cannot be undone.')">
          @csrf
          @method('DELETE')
          <button class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition-colors" type="submit">Delete Content</button>
        </form>
      @endif
      <form method="post" action="{{ route('admin.reports.dismiss', $report) }}" onsubmit="return confirm('Dismiss this report and keep the content? It will no longer be marked as reported.')">
        @csrf
        @method('PUT')
        <button class="px-5 py-2.5 bg-[#4caf50] text-white text-sm font-semibold rounded-md hover:bg-[#429b46] transition-colors" type="submit">Dismiss Report (No Action)</button>
      </form>
    </div>
  @endif
</div>
@endsection
