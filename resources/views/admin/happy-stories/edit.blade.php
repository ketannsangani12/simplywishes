@extends('layouts.admin')

@section('title', 'Simply Wishes - Admin - Edit Happy Story')

@section('content')
<div class="max-w-3xl">
  <div class="mb-6">
    <a class="text-sm text-[#1f4e79] dark:text-brand-blue-dark font-medium hover:underline" href="{{ route('admin.happy-stories.index') }}">&larr; Back to Happy Stories</a>
    <h1 class="text-2xl font-semibold text-[#1f4e79] dark:text-brand-blue-dark mt-2">Edit Happy Story #{{ $story->hs_id }}</h1>
  </div>

  <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow border border-border-light dark:border-border-dark p-6 sm:p-8">
    <form class="space-y-5" method="post" action="{{ route('admin.happy-stories.update', $story) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

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

      <div class="grid gap-5 sm:grid-cols-2">
        <div class="space-y-1">
          <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="user_id">Author</label>
          <select class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="user_id" name="user_id" required>
            @foreach ($users as $user)
              <option value="{{ $user->id }}" @selected((int) old('user_id', $story->user_id) === $user->id)>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('user_id')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-1">
          <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="wish_id">Wish</label>
          <select class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="wish_id" name="wish_id">
            <option value="">None</option>
            @foreach ($wishes as $wish)
              <option value="{{ $wish->w_id }}" @selected((int) old('wish_id', $story->wish_id) === $wish->w_id)>#{{ $wish->w_id }} — {{ $wish->wish_title }}</option>
            @endforeach
          </select>
          @error('wish_id')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="space-y-1">
        <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="story_text">Story Text</label>
        <textarea class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="story_text" name="story_text" rows="6" maxlength="5000" required>{{ old('story_text', $story->story_text) }}</textarea>
        @error('story_text')
          <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="space-y-2">
        <span class="block text-sm font-semibold text-text-light dark:text-text-dark">Story Image</span>
        @if ($story->story_image)
          <div class="flex items-center gap-4">
            <img alt="Current story image" class="h-24 w-24 rounded-md object-cover border border-border-light dark:border-border-dark" src="{{ asset($story->story_image) }}" />
            <label class="flex items-center gap-2 text-sm text-text-light dark:text-text-dark">
              <input class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="remove_image" type="checkbox" value="1" @checked(old('remove_image')) />
              Remove current image
            </label>
          </div>
        @else
          <p class="text-sm text-text-muted-light dark:text-text-muted-dark">No image set.</p>
        @endif
        <input class="block w-full text-sm text-text-muted-light dark:text-text-muted-dark file:mr-3 file:rounded-md file:border-0 file:bg-[#1f70c1] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#185da0]" id="story_image_upload" name="story_image_upload" type="file" accept="image/*" />
        <p class="text-xs text-text-muted-light dark:text-text-muted-dark">Upload a new image to replace the current one (max 5&nbsp;MB).</p>
        @error('story_image_upload')
          <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div class="space-y-1">
          <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="status">Status</label>
          <select class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="status" name="status" required>
            <option value="1" @selected((int) old('status', $story->status) === 1)>Active</option>
            <option value="0" @selected((int) old('status', $story->status) === 0)>Inactive</option>
          </select>
          @error('status')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-1">
          <label class="block text-sm font-semibold text-text-light dark:text-text-dark" for="created_at">Created At</label>
          <input class="block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" id="created_at" name="created_at" type="datetime-local" value="{{ old('created_at', $story->created_at ? \Illuminate\Support\Carbon::parse($story->created_at)->format('Y-m-d\TH:i') : '') }}" required />
          @error('created_at')
            <p class="text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button class="px-5 py-2.5 bg-[#1f70c1] text-white text-sm font-semibold rounded-md hover:bg-[#185da0] transition-colors" type="submit">Save Changes</button>
        <a class="px-5 py-2.5 text-sm font-semibold text-text-muted-light dark:text-text-muted-dark hover:underline" href="{{ route('admin.happy-stories.index') }}">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
