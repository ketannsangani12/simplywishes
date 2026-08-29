<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Donation;
use App\Models\DonationComment;
use App\Models\DonationCommentLike;
use App\Models\ForumComment;
use App\Models\ForumCommentLike;
use App\Models\ForumLike;
use App\Models\ForumPost;
use App\Models\HappyStory;
use App\Models\HappyStoryComment;
use App\Models\HappyStoryCommentLike;
use App\Models\Report;
use App\Models\User;
use App\Models\Wish;
use App\Models\WishComment;
use App\Models\WishCommentLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const TYPE_LABELS = [
        'wish' => 'Wish',
        'donation' => 'Donation',
        'forum' => 'Forum Post',
        'happy_story' => 'Happy Story',
        'wish_comment' => 'Wish Comment',
        'donation_comment' => 'Donation Comment',
        'forum_comment' => 'Forum Comment',
        'happy_story_comment' => 'Happy Story Comment',
        'comment' => 'Comment',
    ];

    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $type = $request->query('type');

        $reports = Report::with(['reporter', 'reportedUser'])
            ->when($status === 'pending', fn ($query) => $query->where('status', 0))
            ->when($status === 'resolved', fn ($query) => $query->where('status', 1))
            ->when($type, fn ($query) => $query->where('reportable_type', $type))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $reports->getCollection()->transform(function (Report $report) {
            $report->resolved = $this->resolveContent($report);

            return $report;
        });

        $pendingCount = Report::where('status', 0)->count();
        $typeLabels = self::TYPE_LABELS;

        return view('admin.reports.index', compact('reports', 'status', 'type', 'pendingCount', 'typeLabels'));
    }

    public function show(Report $report): View
    {
        $report->load(['reporter', 'reportedUser']);
        $resolved = $this->resolveContent($report);

        return view('admin.reports.show', compact('report', 'resolved'));
    }

    public function dismiss(Report $report): RedirectResponse
    {
        $report->status = 1;
        $report->resolved_at = now();
        $report->save();

        return redirect()
            ->route('admin.reports.index')
            ->with('status', 'Report #' . $report->id . ' dismissed. The content was kept and is no longer marked as reported.');
    }

    public function destroyContent(Report $report): RedirectResponse
    {
        $resolved = $this->resolveContent($report);

        if (! $resolved['model']) {
            $report->status = 1;
            $report->resolved_at = now();
            $report->save();

            return redirect()
                ->route('admin.reports.index')
                ->with('status', 'The reported content no longer exists. Report #' . $report->id . ' has been marked as resolved.');
        }

        $type = $resolved['type'];
        $model = $resolved['model'];

        // Snapshot legacy 'comment' report resolutions before the content disappears.
        $legacyReports = Report::where('reportable_type', 'comment')->where('status', 0)->get()
            ->map(fn (Report $legacy) => ['report' => $legacy, 'resolved' => $this->resolveContent($legacy)])
            ->filter(fn (array $entry) => $entry['resolved']['model'] !== null);

        $deletedKeys = DB::transaction(fn () => $this->deleteContent($type, $model));

        Report::where('status', 0)
            ->where(function ($query) use ($deletedKeys) {
                foreach ($deletedKeys as $key) {
                    [$keyType, $keyId] = explode(':', $key);
                    $query->orWhere(fn ($q) => $q->where('reportable_type', $keyType)->where('reportable_id', $keyId));
                }
            })
            ->update(['status' => 1, 'resolved_at' => now()]);

        foreach ($legacyReports as $entry) {
            $key = $entry['resolved']['type'] . ':' . $entry['resolved']['model']->getKey();
            if (in_array($key, $deletedKeys, true)) {
                $entry['report']->status = 1;
                $entry['report']->resolved_at = now();
                $entry['report']->save();
            }
        }

        return redirect()
            ->route('admin.reports.index')
            ->with('status', $resolved['label'] . ' deleted successfully. All related reports have been resolved.');
    }

    /**
     * Resolve a report to its underlying content in a uniform shape.
     *
     * @return array{type: ?string, label: string, model: ?object, title: ?string, text: ?string, image: ?string, author: ?User, siteUrl: ?string}
     */
    private function resolveContent(Report $report): array
    {
        $type = $report->reportable_type;
        $id = $report->reportable_id;

        if ($type === 'comment') {
            $type = $this->resolveLegacyCommentType($report);
        }

        $result = [
            'type' => $type,
            'label' => self::TYPE_LABELS[$type ?? 'comment'] ?? 'Content',
            'model' => null,
            'title' => null,
            'text' => null,
            'image' => null,
            'author' => null,
            'siteUrl' => null,
        ];

        if ($type === null || $id === null) {
            return $result;
        }

        switch ($type) {
            case 'wish':
                if ($wish = Wish::find($id)) {
                    $result['model'] = $wish;
                    $result['title'] = $wish->wish_title;
                    $result['text'] = $wish->wish_description;
                    $result['image'] = $wish->primary_image;
                    $result['author'] = User::find($wish->wished_by);
                    $result['siteUrl'] = route('wishes.show', $wish->w_id);
                }
                break;
            case 'donation':
                if ($donation = Donation::find($id)) {
                    $result['model'] = $donation;
                    $result['title'] = $donation->title;
                    $result['text'] = $donation->description;
                    $result['image'] = $donation->image;
                    $result['author'] = User::find($donation->created_by);
                    $result['siteUrl'] = route('donations.show', $donation->id);
                }
                break;
            case 'forum':
                if ($post = ForumPost::find($id)) {
                    $result['model'] = $post;
                    $result['title'] = $post->e_title;
                    $result['text'] = $post->e_text ?: $post->description;
                    $result['image'] = $post->e_image ?: $post->article_image;
                    $result['author'] = User::find($post->created_by);
                    $result['siteUrl'] = route('forum.show', $post->e_id);
                }
                break;
            case 'happy_story':
                if ($story = HappyStory::find($id)) {
                    $result['model'] = $story;
                    $result['title'] = 'Happy Story #' . $story->hs_id;
                    $result['text'] = $story->story_text;
                    $result['image'] = $story->story_image;
                    $result['author'] = $story->user;
                    $result['siteUrl'] = route('happy.stories.show', $story->hs_id);
                }
                break;
            case 'wish_comment':
                if ($comment = WishComment::find($id)) {
                    $result['model'] = $comment;
                    $result['title'] = 'Comment on Wish #' . $comment->wish_id;
                    $result['text'] = $comment->comment;
                    $result['author'] = User::find($comment->user_id);
                    $result['siteUrl'] = route('wishes.show', $comment->wish_id);
                }
                break;
            case 'donation_comment':
                if ($comment = DonationComment::find($id)) {
                    $result['model'] = $comment;
                    $result['title'] = 'Comment on Donation #' . $comment->donation_id;
                    $result['text'] = $comment->comment;
                    $result['author'] = User::find($comment->user_id);
                    $result['siteUrl'] = route('donations.show', $comment->donation_id);
                }
                break;
            case 'forum_comment':
                if ($comment = ForumComment::find($id)) {
                    $result['model'] = $comment;
                    $result['title'] = 'Comment on Forum Post #' . $comment->forum_id;
                    $result['text'] = $comment->comment;
                    $result['author'] = User::find($comment->user_id);
                    $result['siteUrl'] = route('forum.show', $comment->forum_id);
                }
                break;
            case 'happy_story_comment':
                if ($comment = HappyStoryComment::find($id)) {
                    $result['model'] = $comment;
                    $result['title'] = 'Comment on Happy Story #' . $comment->happy_story_id;
                    $result['text'] = $comment->comment;
                    $result['author'] = User::find($comment->user_id);
                    $result['siteUrl'] = route('happy.stories.show', $comment->happy_story_id);
                }
                break;
        }

        $result['label'] = self::TYPE_LABELS[$type] ?? 'Content';

        return $result;
    }

    /**
     * Legacy reports store every comment as type 'comment'. The system-written
     * default details name the source page, so trust that phrase first — it
     * stays correct even after the comment itself has been deleted. Otherwise
     * fall back to matching the comment id and reported author across the
     * four comment tables.
     */
    private function resolveLegacyCommentType(Report $report): ?string
    {
        $details = strtolower((string) $report->details);
        $phrases = [
            'wish_comment' => 'wish preview',
            'donation_comment' => 'donation preview',
            'forum_comment' => 'forum preview',
            'happy_story_comment' => 'happy story details',
        ];

        foreach ($phrases as $type => $phrase) {
            if (str_contains($details, $phrase)) {
                return $type;
            }
        }

        $candidates = collect([
            'wish_comment' => WishComment::find($report->reportable_id),
            'donation_comment' => DonationComment::find($report->reportable_id),
            'forum_comment' => ForumComment::find($report->reportable_id),
            'happy_story_comment' => HappyStoryComment::find($report->reportable_id),
        ])->filter(fn ($comment) => $comment && (int) $comment->user_id === (int) $report->reported_user_id);

        if ($candidates->count() === 1) {
            return $candidates->keys()->first();
        }

        return null;
    }

    /**
     * Delete the content and its related records. Returns "type:id" keys of
     * everything removed so the matching reports can be resolved.
     *
     * @return list<string>
     */
    private function deleteContent(string $type, object $model): array
    {
        $deleted = [];

        switch ($type) {
            case 'wish':
                $commentIds = WishComment::where('wish_id', $model->w_id)->pluck('id');
                WishCommentLike::whereIn('comment_id', $commentIds)->delete();
                WishComment::whereIn('id', $commentIds)->delete();
                Activity::where('wish_id', $model->w_id)->delete();
                HappyStory::where('wish_id', $model->w_id)->update(['wish_id' => null]);
                $model->delete();

                $deleted = $commentIds->map(fn ($id) => 'wish_comment:' . $id)->all();
                $deleted[] = 'wish:' . $model->w_id;
                break;

            case 'donation':
                $commentIds = DonationComment::where('donation_id', $model->id)->pluck('id');
                DonationCommentLike::whereIn('comment_id', $commentIds)->delete();
                DonationComment::whereIn('id', $commentIds)->delete();
                Activity::where('donation_id', $model->id)->delete();
                $model->delete();

                $deleted = $commentIds->map(fn ($id) => 'donation_comment:' . $id)->all();
                $deleted[] = 'donation:' . $model->id;
                break;

            case 'forum':
                $commentIds = ForumComment::where('forum_id', $model->e_id)->pluck('id');
                ForumCommentLike::whereIn('comment_id', $commentIds)->delete();
                ForumComment::whereIn('id', $commentIds)->delete();
                ForumLike::where('forum_id', $model->e_id)->delete();
                $model->delete();

                $deleted = $commentIds->map(fn ($id) => 'forum_comment:' . $id)->all();
                $deleted[] = 'forum:' . $model->e_id;
                break;

            case 'happy_story':
                $commentIds = HappyStoryComment::where('happy_story_id', $model->hs_id)->pluck('id');
                HappyStoryCommentLike::whereIn('comment_id', $commentIds)->delete();
                HappyStoryComment::whereIn('id', $commentIds)->delete();
                DB::table('happy_story_likes')->where('happy_story_id', $model->hs_id)->delete();
                Activity::where('happy_story_id', $model->hs_id)->delete();
                $model->delete();
                $this->deleteStoryImage($model);

                $deleted = $commentIds->map(fn ($id) => 'happy_story_comment:' . $id)->all();
                $deleted[] = 'happy_story:' . $model->hs_id;
                break;

            case 'wish_comment':
            case 'donation_comment':
            case 'forum_comment':
            case 'happy_story_comment':
                [$commentClass, $likeClass] = match ($type) {
                    'wish_comment' => [WishComment::class, WishCommentLike::class],
                    'donation_comment' => [DonationComment::class, DonationCommentLike::class],
                    'forum_comment' => [ForumComment::class, ForumCommentLike::class],
                    'happy_story_comment' => [HappyStoryComment::class, HappyStoryCommentLike::class],
                };

                $commentIds = $commentClass::where('id', $model->id)
                    ->orWhere('parent_id', $model->id)
                    ->pluck('id');
                $likeClass::whereIn('comment_id', $commentIds)->delete();
                $commentClass::whereIn('id', $commentIds)->delete();

                $deleted = $commentIds->map(fn ($id) => $type . ':' . $id)->all();
                break;
        }

        return $deleted;
    }

    private function deleteStoryImage(HappyStory $story): void
    {
        if (! $story->story_image || ! str_starts_with($story->story_image, 'uploads/happy-stories/')) {
            return;
        }

        $filePath = public_path($story->story_image);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
