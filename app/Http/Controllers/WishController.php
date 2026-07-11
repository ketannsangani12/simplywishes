<?php

namespace App\Http\Controllers;

use App\Mail\WishCreated;
use App\Mail\WishFulfilled;
use App\Mail\WishGrantorConfirmation;
use App\Mail\WishGrantorFulfilled;
use App\Mail\WishGranted;
use App\Models\Donation;
use App\Models\User;
use App\Models\Wish;
use App\Models\WishComment;
use App\Models\WishCommentLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WishController extends Controller
{
    private function storeUploadedWishImage(\Illuminate\Http\UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = Str::uuid()->toString() . '.' . $extension;

        $candidateDirectories = [
            public_path('uploads/wishes'),
            base_path('../public_html/uploads/wishes'),
        ];

        $uploadDirectory = null;
        foreach ($candidateDirectories as $directory) {
            $parentDirectory = dirname($directory);
            if (is_dir($directory) || is_dir($parentDirectory)) {
                $uploadDirectory = $directory;
                break;
            }
        }

        $uploadDirectory ??= public_path('uploads/wishes');

        File::ensureDirectoryExists($uploadDirectory);
        $file->move($uploadDirectory, $filename);

        return 'uploads/wishes/' . $filename;
    }

    public function create(): View
    {
        return view('users.user.create-wish');
    }

    public function store(Request $request): RedirectResponse
    {
        $isDraft = $request->input('action') === 'draft';

        $rules = $isDraft ? [
            'wish_title' => ['nullable', 'string', 'max:100'],
            'wish_description' => ['nullable', 'string'],
            'wish_date' => ['nullable', 'date_format:Y-m-d'],
            'funding' => ['nullable', 'in:yes,no'],
            'payment' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'numeric', 'min:0'],
            'non_financial_method' => ['nullable', 'string', 'max:100'],
            'description_of_way' => ['nullable', 'string'],
            'wish_image_upload' => ['nullable', 'image', 'max:5120'],
            'wish_image_default' => ['nullable', 'string', 'max:500'],
        ] : [
            'wish_title' => ['required', 'string', 'max:100'],
            'wish_description' => ['nullable', 'string'],
            'wish_date' => ['required', 'date_format:Y-m-d'],
            'funding' => ['required', 'in:yes,no'],
            'payment' => ['nullable', 'required_if:funding,yes', 'string', 'max:255'],
            'contact' => ['nullable', 'required_if:funding,yes', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'required_if:funding,yes', 'numeric', 'min:0'],
            'non_financial_method' => ['nullable', 'required_if:funding,no', 'string', 'max:100'],
            'description_of_way' => ['nullable', 'required_if:funding,no', 'string'],
            'wish_image_upload' => ['nullable', 'image', 'max:5120'],
            'wish_image_default' => ['nullable', 'string', 'max:500'],
            'i_agree_decide' => ['accepted'],
        ];

        $validated = $request->validate($rules);

        $user = $request->user();

        $primaryImage = null;
        if ($request->hasFile('wish_image_upload')) {
            $primaryImage = $this->storeUploadedWishImage($request->file('wish_image_upload'));
        } elseif (!empty($validated['wish_image_default'])) {
            $defaultImage = $validated['wish_image_default'];
            if (filter_var($defaultImage, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($defaultImage);
                $defaultImage = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $defaultImage;
            }
            $primaryImage = $defaultImage;
        }

        $wish = Wish::create([
            'wished_by' => $user->id,
            'wish_title' => $validated['wish_title'] ?? null,
            'summary_title' => $validated['wish_title'] ?? null,
            'wish_description' => $validated['wish_description'] ?? null,
            'primary_image' => $primaryImage,
            'expected_cost' => $validated['expected_cost'] ?? null,
            'expected_date' => $validated['wish_date'] ?? null,
            'financial_assistance' => ($validated['funding'] ?? null) === 'yes' ? ($validated['payment'] ?? null) : null,
            'non_pay_option' => ($validated['funding'] ?? null) === 'no' ? 1 : 0,
            'way_of_wish' => ($validated['funding'] ?? null) === 'no' ? ($validated['non_financial_method'] ?? null) : null,
            'description_of_way' => ($validated['funding'] ?? null) === 'no' ? ($validated['description_of_way'] ?? null) : null,
            'show_mail_status' => ($validated['funding'] ?? null) === 'yes' ? 1 : 0,
            'show_mail' => ($validated['funding'] ?? null) === 'yes' ? ($validated['contact'] ?? null) : null,
            'i_agree_decide' => $isDraft ? 0 : 1,
            'wish_status' => $isDraft ? 0 : 1,
            'wish_progress_status' => 0,
            'created_at' => now(),
        ]);

        if (!$isDraft) {
            Mail::to($user->email)->send(new WishCreated($wish, $user));
            $wish->forceFill(['wish_email_status' => 1])->save();
        }

        return redirect()
            ->route('wishes.create')
            ->with('status', $isDraft ? 'Your wish draft has been saved.' : 'Your wish has been created successfully.');
    }

    public function active(): View
    {
        $userId = Auth::id();
        $wishes = Wish::where('wish_status', 1)
            ->where('wish_progress_status', 0)
            ->orderByDesc('w_id')
            ->get();

        $grantedWishes = Wish::where('wish_status', 1)
            ->where('wish_progress_status', 2)
            ->orderByDesc('w_id')
            ->get();

        $grantedDonations = Donation::where('status', 3)
            ->orderByDesc('id')
            ->get();

        $inProgressWishes = Wish::where('wish_status', 1)
            ->where('wish_progress_status', 1)
            ->orderByDesc('w_id')
            ->get();

        $donations = Donation::whereIn('status', [1, 2])
            ->orderByDesc('id')
            ->get();

        $userIds = $wishes->pluck('wished_by')
            ->merge($donations->pluck('created_by'))
            ->merge($grantedDonations->pluck('created_by'))
            ->unique()
            ->filter()
            ->all();
        $userMap = User::whereIn('id', $userIds)->get()->keyBy('id');

        $favWishIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'fav')
            ->whereNotNull('wish_id')
            ->pluck('wish_id')
            ->all();
        $likeWishIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'like')
            ->whereNotNull('wish_id')
            ->pluck('wish_id')
            ->all();

        $favDonationIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'fav')
            ->whereNotNull('donation_id')
            ->pluck('donation_id')
            ->all();
        $likeDonationIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'like')
            ->whereNotNull('donation_id')
            ->pluck('donation_id')
            ->all();

        return view('users.user.active-wishes', compact(
            'wishes',
            'grantedWishes',
            'grantedDonations',
            'inProgressWishes',
            'donations',
            'userMap',
            'favWishIds',
            'likeWishIds',
            'favDonationIds',
            'likeDonationIds'
        ));
    }

    public function drafts(): View
    {
        $wishes = Wish::where('wished_by', Auth::id())
            ->where('wish_status', 0)
            ->orderByDesc('w_id')
            ->get();

        return view('users.user.wish-drafts', compact('wishes'));
    }

    public function show(int $wish): View
    {
        $userId = Auth::id();
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $creator = \App\Models\User::where('id', $wish->wished_by)->first();
        $grantedBy = $wish->granted_by ? \App\Models\User::where('id', $wish->granted_by)->first() : null;
        $reportedCommentIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->where(function ($query) use ($userId) {
                $query->whereNull('reported_user_id')
                    ->orWhere('reported_user_id', '!=', $userId);
            })
            ->pluck('reportable_id')
            ->all();
        $reportedCommentUserIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->whereNotNull('reported_user_id')
            ->where('reported_user_id', '!=', $userId)
            ->pluck('reported_user_id')
            ->unique()
            ->all();
        $comments = WishComment::with([
                'user',
                'replies' => fn ($query) => $query
                    ->when($reportedCommentIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('id', $reportedCommentIds))
                    ->when($reportedCommentUserIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('user_id', $reportedCommentUserIds))
                    ->with('user')
                    ->withCount('likes'),
            ])
            ->withCount('likes')
            ->where('wish_id', $wish->w_id)
            ->whereNull('parent_id')
            ->when($reportedCommentIds !== [], fn ($query) => $query->whereNotIn('id', $reportedCommentIds))
            ->when($reportedCommentUserIds !== [], fn ($query) => $query->whereNotIn('user_id', $reportedCommentUserIds))
            ->latest()
            ->get();

        $favWishIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'fav')
            ->whereNotNull('wish_id')
            ->pluck('wish_id')
            ->all();
        $likeWishIds = \App\Models\Activity::where('user_id', $userId)
            ->where('type', 'like')
            ->whereNotNull('wish_id')
            ->pluck('wish_id')
            ->all();
        $hasReportedWish = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'wish')
            ->where('reportable_id', $wish->w_id)
            ->exists();

        $commentIds = $comments->pluck('id')
            ->merge($comments->flatMap(fn ($comment) => $comment->replies->pluck('id')))
            ->unique()
            ->values();

        $likedCommentIds = WishCommentLike::where('user_id', $userId)
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->all();

        return view('users.user.wish-preview', compact('wish', 'creator', 'grantedBy', 'comments', 'likedCommentIds', 'favWishIds', 'likeWishIds', 'hasReportedWish'));
    }

    public function report(Request $request, int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wish_status', 1)
            ->firstOrFail();

        if ((int) $wish->wished_by === (int) Auth::id()) {
            return redirect()
                ->route('wishes.show', $wish->w_id)
                ->with('status', 'You cannot report your own wish.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'wish',
                'reportable_id' => $wish->w_id,
            ],
            [
                'reported_user_id' => $wish->wished_by,
                'reason' => 'Wish',
                'details' => $request->input('details', 'Wish reported from wish preview.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('wishes.active')
            ->with('status', 'Wish reported successfully.');
    }

    public function storeComment(Request $request, int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            WishComment::where('id', $parentId)
                ->where('wish_id', $wish->w_id)
                ->firstOrFail();
        }

        WishComment::create([
            'wish_id' => $wish->w_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Comment posted successfully.');
    }

    public function destroyComment(int $wish, int $comment): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $comment = WishComment::where('id', $comment)
            ->where('wish_id', $wish->w_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $commentIds = WishComment::where('id', $comment->id)
            ->orWhere('parent_id', $comment->id)
            ->pluck('id');

        WishCommentLike::whereIn('comment_id', $commentIds)->delete();
        WishComment::whereIn('id', $commentIds)->delete();

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Comment deleted successfully.');
    }

    public function updateComment(Request $request, int $wish, int $comment): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $comment = WishComment::where('id', $comment)
            ->where('wish_id', $wish->w_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update([
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Comment updated successfully.');
    }

    public function toggleCommentLike(int $wish, int $comment): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $comment = WishComment::where('id', $comment)
            ->where('wish_id', $wish->w_id)
            ->firstOrFail();

        $like = WishCommentLike::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            WishCommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('wishes.show', $wish->w_id);
    }

    public function reportComment(Request $request, int $wish, int $comment): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where(function ($query) {
                $query->where('wished_by', Auth::id())
                    ->orWhere('wish_status', 1);
            })
            ->firstOrFail();

        $comment = WishComment::with('user')
            ->where('id', $comment)
            ->where('wish_id', $wish->w_id)
            ->firstOrFail();

        if ((int) $comment->user_id === (int) Auth::id()) {
            return redirect()
                ->route('wishes.show', $wish->w_id)
                ->with('status', 'You cannot report your own comment.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'wish_comment',
                'reportable_id' => $comment->id,
            ],
            [
                'reported_user_id' => $comment->user_id,
                'reason' => 'Comment',
                'details' => $request->input('details', 'Comment reported from wish preview.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Comment reported successfully.');
    }

    public function edit(int $wish): View
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->where('wish_progress_status', 0)
            ->firstOrFail();

        return view('users.user.create-wish', compact('wish'));
    }

    public function update(Request $request, int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->where('wish_progress_status', 0)
            ->firstOrFail();

        $isDraft = $request->input('action') === 'draft';

        $rules = $isDraft ? [
            'wish_title' => ['nullable', 'string', 'max:100'],
            'wish_description' => ['nullable', 'string'],
            'wish_date' => ['nullable', 'date_format:Y-m-d'],
            'funding' => ['nullable', 'in:yes,no'],
            'payment' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'numeric', 'min:0'],
            'non_financial_method' => ['nullable', 'string', 'max:100'],
            'description_of_way' => ['nullable', 'string'],
            'wish_image_upload' => ['nullable', 'image', 'max:5120'],
            'wish_image_default' => ['nullable', 'string', 'max:500'],
        ] : [
            'wish_title' => ['required', 'string', 'max:100'],
            'wish_description' => ['nullable', 'string'],
            'wish_date' => ['required', 'date_format:Y-m-d'],
            'funding' => ['required', 'in:yes,no'],
            'payment' => ['nullable', 'required_if:funding,yes', 'string', 'max:255'],
            'contact' => ['nullable', 'required_if:funding,yes', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'required_if:funding,yes', 'numeric', 'min:0'],
            'non_financial_method' => ['nullable', 'required_if:funding,no', 'string', 'max:100'],
            'description_of_way' => ['nullable', 'required_if:funding,no', 'string'],
            'wish_image_upload' => ['nullable', 'image', 'max:5120'],
            'wish_image_default' => ['nullable', 'string', 'max:500'],
            'i_agree_decide' => ['accepted'],
        ];

        $validated = $request->validate($rules);

        $primaryImage = $wish->primary_image;
        if ($request->hasFile('wish_image_upload')) {
            $primaryImage = $this->storeUploadedWishImage($request->file('wish_image_upload'));
        } elseif (!empty($validated['wish_image_default'])) {
            $defaultImage = $validated['wish_image_default'];
            if (filter_var($defaultImage, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($defaultImage);
                $defaultImage = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $defaultImage;
            }
            $primaryImage = $defaultImage;
        }

        $wish->fill([
            'wish_title' => $validated['wish_title'] ?? null,
            'summary_title' => $validated['wish_title'] ?? null,
            'wish_description' => $validated['wish_description'] ?? null,
            'primary_image' => $primaryImage,
            'expected_cost' => $validated['expected_cost'] ?? null,
            'expected_date' => $validated['wish_date'] ?? null,
            'financial_assistance' => ($validated['funding'] ?? null) === 'yes' ? ($validated['payment'] ?? null) : null,
            'non_pay_option' => ($validated['funding'] ?? null) === 'no' ? 1 : 0,
            'way_of_wish' => ($validated['funding'] ?? null) === 'no' ? ($validated['non_financial_method'] ?? null) : null,
            'description_of_way' => ($validated['funding'] ?? null) === 'no' ? ($validated['description_of_way'] ?? null) : null,
            'show_mail_status' => ($validated['funding'] ?? null) === 'yes' ? 1 : 0,
            'show_mail' => ($validated['funding'] ?? null) === 'yes' ? ($validated['contact'] ?? null) : null,
            'i_agree_decide' => $isDraft ? 0 : 1,
            'wish_status' => $isDraft ? 0 : 1,
        ]);

        $wish->save();

        if (!$isDraft && (int) $wish->wish_email_status !== 1) {
            Mail::to($request->user()->email)->send(new WishCreated($wish, $request->user()));
            $wish->forceFill(['wish_email_status' => 1])->save();
        }

        return redirect()
            ->route('wishes.drafts')
            ->with('status', $isDraft ? 'Your wish draft has been updated.' : 'Your wish has been published.');
    }

    public function destroy(int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->where('wish_progress_status', 0)
            ->firstOrFail();

        $wish->delete();

        return redirect()
            ->route('wishes.drafts')
            ->with('status', 'Wish draft deleted.');
    }

    public function grant(Request $request, int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wish_status', 1)
            ->where('wish_progress_status', 0)
            ->firstOrFail();

        if ((int) $wish->wished_by === (int) Auth::id()) {
            return redirect()
                ->route('wishes.show', $wish->w_id)
                ->with('status', 'You cannot grant your own wish.');
        }

        if ((int) $wish->non_pay_option === 1) {
            $request->validate([
                'non_financial_agreement' => ['accepted'],
            ], [
                'non_financial_agreement.accepted' => 'You must agree to the non-financial wish conditions before granting this wish.',
            ]);
        }

        $timestamp = now()->format('Y-m-d H:i:s');

        $wish->forceFill([
            'granted_by' => Auth::id(),
            'granted_date' => $timestamp,
            'process_status' => 1,
            'process_granted_by' => Auth::id(),
            'process_granted_date' => $timestamp,
            'wish_progress_status' => 1,
            'date_updated' => now(),
        ])->save();

        $creator = User::find($wish->wished_by);
        $grantor = $request->user();

        if ($creator && $grantor) {
            Mail::to($creator->email)->send(new WishGranted($wish, $creator, $grantor));
            Mail::to($grantor->email)->send(new WishGrantorConfirmation($wish, $creator, $grantor));
        }

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Wish granted successfully. It is now in progress.');
    }

    public function fulfill(int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->where('wish_status', 1)
            ->where('wish_progress_status', 1)
            ->firstOrFail();

        $wish->forceFill([
            'fulfilled_by' => Auth::id(),
            'fulfilled_date' => now(),
            'wish_progress_status' => 2,
            'date_updated' => now(),
        ])->save();

        $creator = Auth::user();
        if ($creator) {
            Mail::to($creator->email)->send(new WishFulfilled($wish, $creator));
        }

        $grantor = $wish->granted_by ? User::find($wish->granted_by) : null;
        if ($grantor) {
            Mail::to($grantor->email)->send(new WishGrantorFulfilled($wish, $grantor));
        }

        return redirect()
            ->route('wishes.show', $wish->w_id)
            ->with('status', 'Wish fulfilled successfully. It is now granted.');
    }
}
