@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin - Reported Content')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark">Reported Content</h1>
    <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">Content reported by members. {{ $pendingCount }} pending {{ Str::plural('report', $pendingCount) }}.</p>
  </div>
  <form class="flex flex-wrap items-center gap-2" method="get" action="{{ route('admin.reports.index') }}">
    <input name="status" type="hidden" value="{{ $status }}" />
    <select class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" name="type" onchange="this.form.submit()">
      <option value="">All types</option>
      @foreach ($typeLabels as $value => $label)
        <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
      @endforeach
    </select>
  </form>
</div>

<div class="flex items-center gap-2 mb-4">
  @foreach (['pending' => 'Pending', 'resolved' => 'Resolved', 'all' => 'All'] as $value => $label)
    <a class="px-4 py-2 text-sm font-semibold rounded-md transition-colors {{ $status === $value ? 'bg-[#1f70c1] text-white' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-light dark:text-text-dark hover:bg-slate-50 dark:hover:bg-slate-800' }}"
      href="{{ route('admin.reports.index', array_filter(['status' => $value, 'type' => $type])) }}">{{ $label }}</a>
  @endforeach
</div>

<div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-border-light dark:divide-border-dark text-sm">
      <thead class="bg-slate-50 dark:bg-slate-900/40">
        <tr>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">ID</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Type</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Content</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Reported User</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Reported By</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Reported On</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Status</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-light dark:divide-border-dark">
        @forelse ($reports as $report)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/30">
            <td class="px-4 py-3 font-medium">{{ $report->id }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-xs font-semibold text-text-light dark:text-text-dark whitespace-nowrap">{{ $report->resolved['label'] }}</span>
            </td>
            <td class="px-4 py-3 max-w-xs">
              @if ($report->resolved['model'])
                @if ($report->resolved['title'])
                  <p class="font-medium">{{ Str::limit($report->resolved['title'], 60) }}</p>
                @endif
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark line-clamp-2">{{ Str::limit($report->resolved['text'], 100) }}</p>
              @else
                <span class="text-xs italic text-text-muted-light dark:text-text-muted-dark">Content deleted or unavailable</span>
              @endif
            </td>
            <td class="px-4 py-3">
              @if ($report->reportedUser)
                <p class="font-medium">{{ $report->reportedUser->name }}</p>
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $report->reportedUser->email }}</p>
              @else
                <span class="text-text-muted-light dark:text-text-muted-dark">&mdash;</span>
              @endif
            </td>
            <td class="px-4 py-3">
              @if ($report->reporter)
                <p class="font-medium">{{ $report->reporter->name }}</p>
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $report->reporter->email }}</p>
              @else
                <span class="text-text-muted-light dark:text-text-muted-dark">&mdash;</span>
              @endif
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              {{ $report->created_at ? \Illuminate\Support\Carbon::parse($report->created_at)->format('M d, Y') : '—' }}
            </td>
            <td class="px-4 py-3">
              @if ((int) $report->status === 0)
                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Pending</span>
              @else
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Resolved</span>
              @endif
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a class="px-3 py-1.5 bg-[#1f70c1] text-white text-xs font-semibold rounded-md hover:bg-[#185da0] transition-colors" href="{{ route('admin.reports.show', $report) }}">View</a>
                @if ((int) $report->status === 0)
                  @if ($report->resolved['model'])
                    <form method="post" action="{{ route('admin.reports.content.destroy', $report) }}" onsubmit="return confirm('Delete this {{ strtolower($report->resolved['label']) }} and all its related data? This cannot be undone.')">
                      @csrf
                      @method('DELETE')
                      <button class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700 transition-colors" type="submit">Delete</button>
                    </form>
                  @endif
                  <form method="post" action="{{ route('admin.reports.dismiss', $report) }}" onsubmit="return confirm('Dismiss this report and keep the content? It will no longer be marked as reported.')">
                    @csrf
                    @method('PUT')
                    <button class="px-3 py-1.5 bg-[#4caf50] text-white text-xs font-semibold rounded-md hover:bg-[#429b46] transition-colors" type="submit">Dismiss</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-10 text-center text-text-muted-light dark:text-text-muted-dark" colspan="8">No reports found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-6">
  {{ $reports->links() }}
</div>
@endsection
