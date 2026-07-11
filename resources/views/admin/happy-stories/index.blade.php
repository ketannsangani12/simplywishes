@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin - Happy Stories')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark">Happy Stories</h1>
    <p class="text-sm text-text-muted-light dark:text-text-muted-dark mt-1">Manage the happy stories shared by members. Inactive stories are hidden from the site.</p>
  </div>
  <form class="flex flex-wrap items-center gap-2" method="get" action="{{ route('admin.happy-stories.index') }}">
    <input class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" name="search" type="text" placeholder="Search story, author, wish..." value="{{ $search }}" />
    <select class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" name="status">
      <option value="">All statuses</option>
      <option value="1" @selected($status === '1')>Active</option>
      <option value="0" @selected($status === '0')>Inactive</option>
    </select>
    <button class="px-4 py-2 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Filter</button>
    @if ($search !== '' || ($status !== null && $status !== ''))
      <a class="px-4 py-2 text-sm font-semibold text-text-muted-light dark:text-text-muted-dark hover:underline" href="{{ route('admin.happy-stories.index') }}">Reset</a>
    @endif
  </form>
</div>

<div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-border-light dark:divide-border-dark text-sm">
      <thead class="bg-slate-50 dark:bg-slate-900/40">
        <tr>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">ID</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Image</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Story</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Author</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Wish</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Created</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Status</th>
          <th class="px-4 py-3 text-left font-semibold text-text-muted-light dark:text-text-muted-dark">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-light dark:divide-border-dark">
        @forelse ($stories as $story)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/30">
            <td class="px-4 py-3 font-medium">{{ $story->hs_id }}</td>
            <td class="px-4 py-3">
              @if ($story->story_image)
                <img alt="Story image" class="h-12 w-12 rounded-md object-cover" src="{{ asset($story->story_image) }}" />
              @else
                <span class="text-text-muted-light dark:text-text-muted-dark">&mdash;</span>
              @endif
            </td>
            <td class="px-4 py-3 max-w-xs">
              <p class="line-clamp-2">{{ \Illuminate\Support\Str::limit($story->story_text, 120) }}</p>
            </td>
            <td class="px-4 py-3">
              @if ($story->user)
                <p class="font-medium">{{ $story->user->name }}</p>
                <p class="text-xs text-text-muted-light dark:text-text-muted-dark">{{ $story->user->email }}</p>
              @else
                <span class="text-text-muted-light dark:text-text-muted-dark">&mdash;</span>
              @endif
            </td>
            <td class="px-4 py-3 max-w-[180px]">
              {{ $story->wish?->wish_title ?? '—' }}
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              {{ $story->created_at ? \Illuminate\Support\Carbon::parse($story->created_at)->format('M d, Y') : '—' }}
            </td>
            <td class="px-4 py-3">
              @if ((int) $story->status === 1)
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Active</span>
              @else
                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Inactive</span>
              @endif
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <form method="post" action="{{ route('admin.happy-stories.status', $story) }}">
                  @csrf
                  @method('PUT')
                  <select class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-2 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" name="status" onchange="this.form.submit()">
                    <option value="1" @selected((int) $story->status === 1)>Active</option>
                    <option value="0" @selected((int) $story->status === 0)>Inactive</option>
                  </select>
                </form>
                <a class="px-3 py-1.5 bg-[#1f70c1] text-white text-xs font-semibold rounded-md hover:bg-[#185da0] transition-colors" href="{{ route('admin.happy-stories.edit', $story) }}">Edit</a>
                <form method="post" action="{{ route('admin.happy-stories.destroy', $story) }}" onsubmit="return confirm('Delete happy story #{{ $story->hs_id }}? This will also remove its comments, likes, and activity. This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700 transition-colors" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-10 text-center text-text-muted-light dark:text-text-muted-dark" colspan="8">No happy stories found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-6">
  {{ $stories->links() }}
</div>
@endsection
