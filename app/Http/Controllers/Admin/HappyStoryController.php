<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\HappyStory;
use App\Models\HappyStoryComment;
use App\Models\HappyStoryCommentLike;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HappyStoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $stories = HappyStory::with(['user', 'wish'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('story_text', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('wish', fn ($q) => $q->where('wish_title', 'like', "%{$search}%"));
                });
            })
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', (int) $status))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.happy-stories.index', compact('stories', 'search', 'status'));
    }

    public function edit(HappyStory $story): View
    {
        $story->load(['user', 'wish']);

        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $wishes = Wish::orderBy('wish_title')->get(['w_id', 'wish_title']);

        return view('admin.happy-stories.edit', compact('story', 'users', 'wishes'));
    }

    public function update(Request $request, HappyStory $story): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'wish_id' => ['nullable', 'integer', 'exists:wishes,w_id'],
            'story_text' => ['required', 'string', 'max:5000'],
            'story_image_upload' => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'status' => ['required', 'integer', 'in:0,1'],
            'created_at' => ['required', 'date'],
        ]);

        $storyImage = $story->story_image;

        if ($request->boolean('remove_image')) {
            $storyImage = null;
        }

        if ($request->hasFile('story_image_upload')) {
            $file = $request->file('story_image_upload');
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $uploadDirectory = public_path('uploads/happy-stories');
            File::ensureDirectoryExists($uploadDirectory);
            $file->move($uploadDirectory, $fileName);

            $storyImage = 'uploads/happy-stories/' . $fileName;
        }

        $story->fill([
            'user_id' => $validated['user_id'],
            'wish_id' => $validated['wish_id'] ?? null,
            'story_text' => trim($validated['story_text']),
            'story_image' => $storyImage,
            'status' => $validated['status'],
            'created_at' => Carbon::parse($validated['created_at']),
        ]);
        $story->save();

        return redirect()
            ->route('admin.happy-stories.index')
            ->with('status', 'Happy story #' . $story->hs_id . ' updated successfully.');
    }

    public function destroy(HappyStory $story): RedirectResponse
    {
        DB::transaction(function () use ($story) {
            $commentIds = HappyStoryComment::where('happy_story_id', $story->hs_id)->pluck('id');

            HappyStoryCommentLike::whereIn('comment_id', $commentIds)->delete();
            HappyStoryComment::whereIn('id', $commentIds)->delete();
            DB::table('happy_story_likes')->where('happy_story_id', $story->hs_id)->delete();
            Activity::where('happy_story_id', $story->hs_id)->delete();

            $story->delete();
        });

        if ($story->story_image && str_starts_with($story->story_image, 'uploads/happy-stories/')) {
            $filePath = public_path($story->story_image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        return redirect()
            ->route('admin.happy-stories.index')
            ->with('status', 'Happy story #' . $story->hs_id . ' and all its related data deleted successfully.');
    }

    public function updateStatus(Request $request, HappyStory $story): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $story->status = $validated['status'];
        $story->save();

        return redirect()
            ->back()
            ->with('status', 'Happy story #' . $story->hs_id . ' marked as ' . ($story->status ? 'Active' : 'Inactive') . '.');
    }
}
