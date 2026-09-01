<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use App\Models\ForumCommentLike;
use App\Models\ForumLike;
use App\Models\ForumPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Description is only mandatory for an article — the video form
        // intentionally leaves it optional (no asterisk in the UI, since a
        // video post leans on the video itself), so both content fields
        // are 'nullable' here and the *article*-only requirement is
        // enforced explicitly below instead of via a blanket 'required'
        // that would wrongly force video posts to have one too.
        $validator = Validator::make($request->all(), [
            'post_type' => ['required', 'in:article,video'],
            $titleField => ['nullable', 'string', 'max:250'],
            $contentField => ['nullable', 'string'],
            $videoUrlField => ['nullable', 'url', 'max:2048'],
            $videoFileField => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,m4v,avi', 'max:51200'],
            $thumbnailField => ['nullable', 'image', 'max:10240'],
        ]);

        // A single, plain-language error covers every "you left a mandatory
        // field empty" case, instead of Laravel's default per-field message
        // (e.g. "The video content field is required.") which doesn't map
        // to anything the user can see on the form.
        $validator->after(function ($validator) use ($request, $postType, $titleField, $contentField) {
            $missingMandatory = ! $request->filled($titleField)
                || ($postType === 'article' && ! $request->filled($contentField))
                || ($postType === 'video' && ! $request->filled('video_featured_video_url') && ! $request->hasFile('video_featured_video_file'))
                || ($postType === 'video' && ! $request->hasFile('video_thumbnail'));

            if ($missingMandatory) {
                $validator->errors()->add('mandatory', 'Please complete all mandatory fields before proceeding.');
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

        $redirectTab = $validated['post_type'] === 'video' ? 'videos' : 'articles';

        return redirect()
            ->route('forum', ['tab' => $redirectTab])
            ->with('status', 'Your forum post has been created successfully.');
    }

    public function edit(int $forum): View
    {
        $post = ForumPost::where('e_id', $forum)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        return view('users.user.edit-forum', compact('post'));
    }

    public function update(Request $request, int $forum): RedirectResponse
    {
        $post = ForumPost::where('e_id', $forum)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $postType = (int) $post->is_video_only === 1 ? 'video' : 'article';
        $titleField = $postType === 'video' ? 'video_title' : 'article_title';
        $contentField = $postType === 'video' ? 'video_content' : 'article_content';
        $videoUrlField = $postType === 'video' ? 'video_featured_video_url' : 'article_featured_video_url';
        $videoFileField = $postType === 'video' ? 'video_featured_video_file' : 'article_featured_video_file';
        $thumbnailField = $postType === 'video' ? 'video_thumbnail' : 'article_thumbnail';

        // See store() above: description is only mandatory for an article.
        $validator = Validator::make($request->all(), [
            $titleField => ['nullable', 'string', 'max:250'],
            $contentField => ['nullable', 'string'],
            $videoUrlField => ['nullable', 'url', 'max:2048'],
            $videoFileField => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,m4v,avi', 'max:51200'],
            $thumbnailField => ['nullable', 'image', 'max:10240'],
        ]);

        $validator->after(function ($validator) use ($request, $postType, $titleField, $contentField, $videoUrlField, $videoFileField, $post) {
            $missingMandatory = ! $request->filled($titleField)
                || ($postType === 'article' && ! $request->filled($contentField))
                || ($postType === 'video'
                    && ! $request->filled($videoUrlField)
                    && ! $request->hasFile($videoFileField)
                    && empty($post->featured_video_url));

            if ($missingMandatory) {
                $validator->errors()->add('mandatory', 'Please complete all mandatory fields before proceeding.');
            }
        });

        $validated = $validator->validate();

        $videoUrl = trim((string) ($validated[$videoUrlField] ?? ''));
        $currentVideoUrl = $post->featured_video_url;
        if ($request->hasFile($videoFileField)) {
            $videoUrl = $this->storeForumUpload($request->file($videoFileField), 'videos');
        } elseif ($videoUrl === '') {
            $videoUrl = $currentVideoUrl;
        }

        if ($videoUrl !== $currentVideoUrl) {
            $this->deleteForumUpload($currentVideoUrl);
        }

        $thumbnailPath = $post->e_image;
        if ($request->hasFile($thumbnailField)) {
            $thumbnailPath = $this->storeForumUpload($request->file($thumbnailField), 'thumbnails');
        }

        if ($thumbnailPath !== $post->e_image) {
            $this->deleteForumUpload($post->e_image);
        }

        $post->update([
            'e_title' => trim($validated[$titleField]),
            'e_text' => trim($validated[$contentField]),
            'description' => trim($validated[$contentField]),
            'e_image' => $thumbnailPath,
            'featured_video_url' => $videoUrl !== '' ? $videoUrl : null,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('status', 'Your forum post has been updated successfully.');
    }

    public function destroy(int $forum): RedirectResponse
    {
        $post = ForumPost::where('e_id', $forum)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $commentIds = ForumComment::where('forum_id', $post->e_id)->pluck('id');

        ForumCommentLike::whereIn('comment_id', $commentIds)->delete();
        ForumComment::whereIn('id', $commentIds)->delete();
        ForumLike::where('forum_id', $post->e_id)->delete();

        $this->deleteForumUpload($post->e_image);
        $this->deleteForumUpload($post->featured_video_url);

        $post->delete();

        return redirect()
            ->route('forum')
            ->with('status', 'Your forum post has been deleted successfully.');
    }

    /**
     * Stream an uploaded forum thumbnail/video back through Laravel instead
     * of relying on the web server to serve it as a static file.
     *
     * Uploaded files are written under Laravel's own public_path(), which is
     * always readable/writable by PHP. On some hosts, though, the web
     * server's actual document root is a different directory (e.g. a
     * "public_html" folder that isn't Laravel's public/), so a plain static
     * URL to that file 404s even though the upload itself succeeded and the
     * file is genuinely sitting on disk. Serving it through a route sidesteps
     * that mismatch entirely, since it only depends on PHP/Laravel routing
     * (which is already working, or none of this app would work), not on
     * the web server's static file document root.
     */
    public function serveMedia(string $folder, string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (! in_array($folder, ['thumbnails', 'videos'], true)) {
            abort(404);
        }

        if (str_contains($filename, '/') || str_contains($filename, '..')) {
            abort(404);
        }

        $path = public_path('uploads/forum/' . $folder . '/' . $filename);

        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path);
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

        $likedPostIds = ForumLike::where('user_id', Auth::id())
            ->whereIn('forum_id', $posts->pluck('e_id'))
            ->pluck('forum_id')
            ->all();

        return view('users.user.forum', compact('tab', 'searchTerm', 'posts', 'likedPostIds'));
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

        $userId = Auth::id();
        $reportedCommentIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->where(function ($query) use ($userId) {
                $query->whereNull('reported_user_id')
                    ->orWhere('reported_user_id', '!=', $userId);
            })
            ->pluck('reportable_id');

        $reportedCommentUserIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->whereNotNull('reported_user_id')
            ->where('reported_user_id', '!=', $userId)
            ->pluck('reported_user_id');

        $likedByCurrentUser = ForumLike::where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->exists();

        $hasReportedForum = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'forum')
            ->where('reportable_id', $post->e_id)
            ->where('status', 0)
            ->exists();

        $comments = $post->comments
            ->whereNull('parent_id')
            ->when($reportedCommentIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $reportedCommentIds))
            ->when($reportedCommentUserIds->isNotEmpty(), fn ($query) => $query->whereNotIn('user_id', $reportedCommentUserIds))
            ->sortByDesc('created_at')
            ->values();

        $commentIds = $comments
            ->pluck('id')
            ->merge($comments->flatMap(fn ($comment) => $comment->replies->pluck('id')))
            ->unique()
            ->values();

        $likedCommentIds = ForumCommentLike::where('user_id', Auth::id())
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->all();

        return view('users.user.forum-details', compact('post', 'likedByCurrentUser', 'likedCommentIds', 'comments', 'hasReportedForum'));
    }

    public function toggleLike(Request $request, int $forum): RedirectResponse|JsonResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);

        $like = ForumLike::where('forum_id', $post->e_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ForumLike::create([
                'forum_id' => $post->e_id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            $liked = true;
        }

        // The heart icon on both the forum listing cards and the post detail
        // page likes instantly via fetch(), matching Happy Stories. Plain
        // (non-JS) form submits still fall back to the old full-page redirect.
        if ($request->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => ForumLike::where('forum_id', $post->e_id)->count(),
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

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('comment_status', 'Comment posted successfully.')
            ->withFragment('comments');
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

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('comment_status', 'Comment updated successfully.')
            ->withFragment('comments');
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

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('comment_status', 'Comment deleted successfully.')
            ->withFragment('comments');
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

        return redirect()->route('forum.show', $post->e_id)->withFragment('comments');
    }

    public function report(Request $request, int $forum): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);

        if ((int) $post->created_by === (int) Auth::id()) {
            return redirect()
                ->route('forum.show', $post->e_id)
                ->with('status', 'You cannot report your own forum post.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'forum',
                'reportable_id' => $post->e_id,
            ],
            [
                'reported_user_id' => $post->created_by,
                'reason' => 'Forum',
                'details' => $request->input('details', 'Forum reported from forum details page.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('status', 'Forum reported successfully.');
    }

    public function reportComment(Request $request, int $forum, int $comment): RedirectResponse
    {
        $post = ForumPost::where('status', 1)->findOrFail($forum);

        $comment = ForumComment::with('user')
            ->where('id', $comment)
            ->where('forum_id', $post->e_id)
            ->firstOrFail();

        if ((int) $comment->user_id === (int) Auth::id()) {
            return redirect()
                ->route('forum.show', $post->e_id)
                ->with('comment_status', 'You cannot report your own comment.')
                ->withFragment('comments');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'forum_comment',
                'reportable_id' => $comment->id,
            ],
            [
                'reported_user_id' => $comment->user_id,
                'reason' => 'Comment',
                'details' => $request->input('details', 'Comment reported from forum preview.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('forum.show', $post->e_id)
            ->with('comment_status', 'Comment reported successfully.')
            ->withFragment('comments');
    }

    private function storeForumUpload($file, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $fileName = Str::uuid()->toString() . '.' . $extension;

        // Always public_path(): serveMedia() below only ever looks for the
        // file there, so writing it anywhere else means it can never be
        // found again regardless of what URL points at it.
        $uploadDirectory = public_path('uploads/forum/' . $folder);
        File::ensureDirectoryExists($uploadDirectory);
        $file->move($uploadDirectory, $fileName);

        return 'uploads/forum/' . $folder . '/' . $fileName;
    }

    private function deleteForumUpload(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/forum/')) {
            return;
        }

        $filePath = public_path($path);
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }
}
