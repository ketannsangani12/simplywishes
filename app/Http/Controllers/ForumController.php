<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use App\Models\ForumCommentLike;
use App\Models\ForumLike;
use App\Models\ForumPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $postType = $request->input('post_type');
        $titleField = $postType === 'video' ? 'video_title' : 'article_title';
        $contentField = $postType === 'video' ? 'video_content' : 'article_content';
        $videoUrlField = $postType === 'video' ? 'video_featured_video_url' : 'article_featured_video_url';
        $videoFileField = $postType === 'video' ? 'video_featured_video_file' : 'article_featured_video_file';
        $thumbnailField = $postType === 'video' ? 'video_thumbnail' : 'article_thumbnail';

        $validator = Validator::make($request->all(), [
            'post_type' => ['required', 'in:article,video'],
            $titleField => ['required', 'string', 'max:250'],
            $contentField => ['required', 'string'],
            $videoUrlField => ['nullable', 'url', 'max:2048'],
            $videoFileField => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,m4v,avi', 'max:51200'],
            $thumbnailField => ['nullable', 'image', 'max:10240'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('post_type') === 'video') {
                if (! $request->filled('video_featured_video_url') && ! $request->hasFile('video_featured_video_file')) {
                    $validator->errors()->add('video_featured_video_url', 'Please provide a video URL or upload a video file.');
                }

                if (! $request->hasFile('video_thumbnail')) {
                    $validator->errors()->add('video_thumbnail', 'Please upload a thumbnail image for the video.');
                }
            }
        });

        $validated = $validator->validate();

        $videoUrl = trim((string) ($validated[$videoUrlField] ?? ''));
        if ($request->hasFile($videoFileField)) {
            $videoUrl = $this->storeForumUpload($request->file($videoFileField), 'videos');
        }

        $thumbnailPath = null;
        if ($request->hasFile($thumbnailField)) {
            $thumbnailPath = $this->storeForumUpload($request->file($thumbnailField), 'thumbnails');
        }

        ForumPost::create([
            'e_title' => trim($validated[$titleField]),
            'e_text' => trim($validated[$contentField]),
            'description' => trim($validated[$contentField]),
            'e_image' => $thumbnailPath,
            'article_image' => null,
            'featured_video_url' => $videoUrl !== '' ? $videoUrl : null,
            'status' => 1,
            'is_video_only' => $validated['post_type'] === 'video' ? 1 : 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('forum', ['tab' => 'contribute'])
            ->with('status', 'Your forum post has been created successfully.')
            ->with('active_contribute_tab', $validated['post_type']);
    }

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'articles');
        if (! in_array($tab, ['articles', 'videos', 'contribute'], true)) {
            $tab = 'articles';
        }

        $searchTerm = trim((string) $request->query('q', ''));

        $postsQuery = ForumPost::query()
            ->with(['creator'])
            ->withCount('likes')
            ->where('status', 1);

        if ($tab === 'articles') {
            $postsQuery->where('is_video_only', 0);
        } elseif ($tab === 'videos') {
            $postsQuery->where('is_video_only', 1);
        }

        if ($searchTerm !== '') {
            $postsQuery->where(function ($query) use ($searchTerm) {
                $query->where('e_title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('e_text', 'like', "%{$searchTerm}%");
            });
        }

        $posts = $postsQuery
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('users.user.forum', compact('tab', 'searchTerm', 'posts'));
    }

    public function show(int $forum): View
    {
        $post = ForumPost::with([
            'creator',
            'comments.user',
            'comments.likes',
            'comments.replies.user',
            'comments.replies.likes',
        ])
            ->where('status', 1)
            ->findOrFail($forum);

        $post->loadCount('likes');

        $likedByCurrentUser = ForumLike::where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->exists();

        $commentIds = $post->comments
            ->pluck('id')
            ->merge($post->comments->flatMap(fn ($comment) => $comment->replies->pluck('id')))
            ->unique()
            ->values();

        $likedCommentIds = ForumCommentLike::where('user_id', Auth::id())
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->all();

        $comments = $post->comments
            ->whereNull('parent_id')
            ->sortByDesc('created_at')
            ->values();

        return view('users.user.forum-details', compact('post', 'likedByCurrentUser', 'likedCommentIds', 'comments'));
    }

    public function toggleLike(int $forum): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);

        $like = ForumLike::where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            ForumLike::create([
                'forum_id' => $post->e_id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('forum.show', $post->e_id);
    }

    public function storeComment(Request $request, int $forum): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            ForumComment::where('id', $parentId)
                ->where('forum_id', $post->e_id)
                ->firstOrFail();
        }

        ForumComment::create([
            'forum_id' => $post->e_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'comment' => trim($validated['comment']),
        ]);

        return redirect()->route('forum.show', $post->e_id)->with('status', 'Comment posted successfully.');
    }

    public function updateComment(Request $request, int $forum, int $comment): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);
        $comment = ForumComment::where('id', $comment)
            ->where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update([
            'comment' => trim($validated['comment']),
        ]);

        return redirect()->route('forum.show', $post->e_id)->with('status', 'Comment updated successfully.');
    }

    public function destroyComment(int $forum, int $comment): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);
        $comment = ForumComment::where('id', $comment)
            ->where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $commentIds = ForumComment::where('id', $comment->id)
            ->orWhere('parent_id', $comment->id)
            ->pluck('id');

        ForumCommentLike::whereIn('comment_id', $commentIds)->delete();
        ForumComment::whereIn('id', $commentIds)->delete();

        return redirect()->route('forum.show', $post->e_id)->with('status', 'Comment deleted successfully.');
    }

    public function toggleCommentLike(int $forum, int $comment): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);
        $comment = ForumComment::where('id', $comment)
            ->where('forum_id', $post->e_id)
            ->firstOrFail();

        $like = ForumCommentLike::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            ForumCommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('forum.show', $post->e_id);
    }

    private function storeForumUpload($file, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $fileName = Str::uuid()->toString() . '.' . $extension;

        $uploadDirectory = public_path('uploads/forum/' . $folder);
        File::ensureDirectoryExists($uploadDirectory);
        $file->move($uploadDirectory, $fileName);

        return 'uploads/forum/' . $folder . '/' . $fileName;
    }
}
