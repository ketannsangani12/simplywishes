<?php

namespace App\Http\Controllers;

use App\Mail\DonationAcceptedCompleted;
use App\Mail\DonationAcceptedFinancial;
use App\Mail\DonationAcceptedCreator;
use App\Mail\DonationCreatorCompleted;
use App\Mail\DonationAcceptedNonFinancial;
use App\Mail\DonationCreated;
use App\Models\Donation;
use App\Models\DonationComment;
use App\Models\DonationCommentLike;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonationController extends Controller
{
    private const MAX_ACTIVE_LISTINGS = 5;

    // Every notification email below is sent synchronously (not queued), so
    // a mail failure (a bad/expired SMTP credential, the mail host being
    // unreachable from production, a provider rate-limit, etc.) would
    // otherwise throw and abort the whole request AFTER the donation had
    // already been created/accepted/completed in the database — the
    // visible symptom being the user never sees the success page/redirect
    // they were expecting (it can look like the action "did nothing" or
    // "the post disappeared"), even though the underlying action actually
    // went through. Notifying the donor/acceptor is a nice-to-have, not the
    // point of the request, so a failure here is logged and swallowed
    // rather than allowed to break the response.
    private function sendMailQuietly(callable $send, string $context): void
    {
        try {
            $send();
        } catch (\Throwable $e) {
            Log::error("Failed to send {$context} email: " . $e->getMessage());
        }
    }

    private function storeUploadedDonationImage(\Illuminate\Http\UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = Str::uuid()->toString() . '.' . $extension;

        // Always public_path(): uploadedImage() below only ever looks for
        // the file there, so writing it anywhere else means it can never be
        // found again regardless of what URL points at it.
        $uploadDirectory = public_path('uploads/donations');
        File::ensureDirectoryExists($uploadDirectory);
        $file->move($uploadDirectory, $filename);

        return 'uploads/donations/' . $filename;
    }

    /**
     * Stream a default donation image back through Laravel rather than
     * relying on the web server to serve public/images/wishes-default/
     * directly (donations reuse the same default image set as wishes — see
     * the picker in create-donation.blade.php). See
     * WishController::defaultImage() for why this goes through PHP.
     */
    public function defaultImage(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (str_contains($filename, '/') || str_contains($filename, '..')) {
            abort(404);
        }

        // See WishController::defaultImage() for why both locations are
        // checked: these are pre-seeded assets that may only exist under
        // public_html on hosts where it's a sibling of this Laravel install.
        $candidates = [
            public_path('images/wishes-default/' . $filename),
            base_path('../public_html/images/wishes-default/' . $filename),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    /**
     * Stream a user-uploaded donation image back through Laravel, for the
     * same reason as defaultImage() above.
     */
    public function uploadedImage(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (str_contains($filename, '/') || str_contains($filename, '..')) {
            abort(404);
        }

        // See WishController::uploadedImage() for why both locations are
        // checked: donations uploaded before the write-path fix may have
        // landed under public_html instead of this app's own public/.
        $candidates = [
            public_path('uploads/donations/' . $filename),
            base_path('../public_html/uploads/donations/' . $filename),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return response()->file($path);
            }
        }

        abort(404);
    }

    public function create(): View
    {
        return view('users.user.create-donation');
    }

    public function edit(int $donation): View
    {
        $donation = Donation::where('id', $donation)
            ->where('created_by', Auth::id())
            ->whereIn('status', [0, 1])
            ->firstOrFail();

        return view('users.user.create-donation', compact('donation'));
    }

    public function drafts(): View
    {
        $donations = Donation::where('created_by', Auth::id())
            ->where('status', 0)
            ->orderByDesc('id')
            ->get();

        return view('users.user.donation-drafts', compact('donations'));
    }

    public function show(int $donation): View
    {
        $userId = Auth::id();
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $creator = User::where('id', $donation->created_by)->first();
        $acceptedBy = $donation->accepted_by ? User::where('id', $donation->accepted_by)->first() : null;
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
        $comments = DonationComment::with([
                'user',
                'replies' => fn ($query) => $query
                    ->when($reportedCommentIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('id', $reportedCommentIds))
                    ->when($reportedCommentUserIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('user_id', $reportedCommentUserIds))
                    ->with('user')
                    ->withCount('likes'),
            ])
            ->withCount('likes')
            ->where('donation_id', $donation->id)
            ->whereNull('parent_id')
            ->when($reportedCommentIds !== [], fn ($query) => $query->whereNotIn('id', $reportedCommentIds))
            ->when($reportedCommentUserIds !== [], fn ($query) => $query->whereNotIn('user_id', $reportedCommentUserIds))
            ->latest()
            ->get();

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
        $hasReportedDonation = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'donation')
            ->where('reportable_id', $donation->id)
            ->where('status', 0)
            ->exists();

        $commentIds = $comments->pluck('id')
            ->merge($comments->flatMap(fn ($comment) => $comment->replies->pluck('id')))
            ->unique()
            ->values();

        $likedCommentIds = DonationCommentLike::where('user_id', $userId)
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->all();

        return view('users.user.donation-preview', compact('donation', 'creator', 'acceptedBy', 'comments', 'likedCommentIds', 'favDonationIds', 'likeDonationIds', 'hasReportedDonation'));
    }

    public function report(Request $request, int $donation): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->whereIn('status', [1, 2, 3])
            ->firstOrFail();

        if ((int) $donation->created_by === (int) Auth::id()) {
            return redirect()
                ->route('donations.show', $donation->id)
                ->with('status', 'You cannot report your own donation.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'donation',
                'reportable_id' => $donation->id,
            ],
            [
                'reported_user_id' => $donation->created_by,
                'reason' => 'Donation',
                'details' => $request->input('details', 'Donation reported from donation preview.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('donations.show', $donation->id)
            ->with('status', 'Donation reported successfully.');
    }

    public function storeComment(Request $request, int $donation): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            DonationComment::where('id', $parentId)
                ->where('donation_id', $donation->id)
                ->firstOrFail();
        }

        DonationComment::create([
            'donation_id' => $donation->id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('donations.show', $donation->id)
            ->with('comment_status', 'Comment posted successfully.')
            ->withFragment('comments');
    }

    public function destroyComment(int $donation, int $comment): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $comment = DonationComment::where('id', $comment)
            ->where('donation_id', $donation->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $commentIds = DonationComment::where('id', $comment->id)
            ->orWhere('parent_id', $comment->id)
            ->pluck('id');

        DonationCommentLike::whereIn('comment_id', $commentIds)->delete();
        DonationComment::whereIn('id', $commentIds)->delete();

        return redirect()
            ->route('donations.show', $donation->id)
            ->with('comment_status', 'Comment deleted successfully.')
            ->withFragment('comments');
    }

    public function updateComment(Request $request, int $donation, int $comment): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $comment = DonationComment::where('id', $comment)
            ->where('donation_id', $donation->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update([
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('donations.show', $donation->id)
            ->with('comment_status', 'Comment updated successfully.')
            ->withFragment('comments');
    }

    public function toggleCommentLike(int $donation, int $comment): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $comment = DonationComment::where('id', $comment)
            ->where('donation_id', $donation->id)
            ->firstOrFail();

        $like = DonationCommentLike::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            DonationCommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('donations.show', $donation->id)->withFragment('comments');
    }

    public function reportComment(Request $request, int $donation, int $comment): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where(function ($query) {
                $query->where('created_by', Auth::id())
                    ->orWhereIn('status', [1, 2, 3]);
            })
            ->firstOrFail();

        $comment = DonationComment::where('id', $comment)
            ->where('donation_id', $donation->id)
            ->firstOrFail();

        if ((int) $comment->user_id === (int) Auth::id()) {
            return redirect()
                ->route('donations.show', $donation->id)
                ->with('comment_status', 'You cannot report your own comment.')
                ->withFragment('comments');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'donation_comment',
                'reportable_id' => $comment->id,
            ],
            [
                'reported_user_id' => $comment->user_id,
                'reason' => 'Comment',
                'details' => $request->input('details', 'Comment reported from donation preview.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('donations.show', $donation->id)
            ->with('comment_status', 'Comment reported successfully.')
            ->withFragment('comments');
    }

    public function store(Request $request): RedirectResponse
    {
        $isDraft = $request->input('action') === 'draft';
        $user = $request->user();

        if (! $isDraft && $this->hasReachedActiveListingLimit((int) $user->id)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'listing_limit' => 'You have reached the maximum limit of 5 active donations. Please remove an existing donation before creating a new one',
                ]);
        }

        // A draft is still built from the same form as a real submission,
        // and shares the same mandatory fields — the only thing a draft is
        // actually exempt from is agreeing to the terms below, since that's
        // tied to the donation going live, not to describing it. Saving a
        // completely blank "draft" was never a meaningful state to allow.
        $rules = [
            'donation_title' => ['required', 'string', 'max:100'],
            'donation_description' => ['nullable', 'string'],
            'donation_funding' => ['required', 'in:yes,no'],
            'donation_payment' => ['nullable', 'required_if:donation_funding,yes', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'required_if:donation_funding,yes', 'numeric', 'gt:0'],
            'donation_method' => ['nullable', 'required_if:donation_funding,no', 'string', 'max:100'],
            // Also fixes a pre-existing gap: this field is marked mandatory
            // in the form ("Add details for your selected method *") for a
            // non-financial donation, but was never actually enforced here.
            'donation_notes' => ['nullable', 'required_if:donation_funding,no', 'string'],
            'donation_image_upload' => ['nullable', 'required_without:donation_image_default', 'image', 'max:5120'],
            'donation_image_default' => ['nullable', 'required_without:donation_image_upload', 'string', 'max:500'],
        ];

        if (! $isDraft) {
            $rules['donation_terms'] = ['accepted'];
        }

        $validated = $request->validate($rules, [
            'donation_image_upload.required_without' => 'Please upload an image or select one from the default gallery.',
            'donation_image_default.required_without' => 'Please upload an image or select one from the default gallery.',
            'donation_notes.required_if' => 'Please provide the details for your selected method.',
            'expected_cost.gt' => 'Please enter a cost greater than zero.',
            'expected_cost.required_if' => 'Expected cost is required.',
        ]);

        $image = null;
        if ($request->hasFile('donation_image_upload')) {
            $image = $this->storeUploadedDonationImage($request->file('donation_image_upload'));
        } elseif (!empty($validated['donation_image_default'])) {
            $defaultImage = $validated['donation_image_default'];
            if (filter_var($defaultImage, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($defaultImage);
                $defaultImage = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $defaultImage;
            }
            $image = $defaultImage;
        }

        $donation = Donation::create([
            'created_by' => $user->id,
            'title' => $validated['donation_title'] ?? null,
            'summary_title' => $validated['donation_title'] ?? null,
            'description' => $validated['donation_description'] ?? null,
            'image' => $image,
            'expected_cost' => $validated['expected_cost'] ?? null,
            'financial_assistance' => ($validated['donation_funding'] ?? null) === 'yes' ? ($validated['donation_payment'] ?? null) : null,
            'non_pay_option' => ($validated['donation_funding'] ?? null) === 'no' ? 1 : 0,
            'way_of_donation' => ($validated['donation_funding'] ?? null) === 'no' ? ($validated['donation_method'] ?? null) : null,
            'description_of_way' => ($validated['donation_funding'] ?? null) === 'no' ? ($validated['donation_notes'] ?? null) : null,
            'i_agree_decide' => $isDraft ? 0 : 1,
            'status' => $isDraft ? 0 : 1,
            'created_at' => now(),
        ]);

        if (!$isDraft) {
            $this->sendMailQuietly(fn () => Mail::to($user->email)->send(new DonationCreated($donation, $user)), 'DonationCreated');
            $donation->forceFill(['donation_email_status' => 1])->save();
        }

        return redirect()
            ->route(
                $isDraft ? 'donations.drafts' : 'donations.show',
                $isDraft ? [] : ['donation' => $donation->id, 'source' => 'active', 'source_tab' => 'current-donations']
            )
            ->with('status', $isDraft ? 'Your donation draft has been saved.' : 'Your donation has been created successfully.');
    }

    public function update(Request $request, int $donation): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where('created_by', Auth::id())
            ->whereIn('status', [0, 1])
            ->firstOrFail();

        $isDraft = $request->input('action') === 'draft';

        if (! $isDraft && $this->hasReachedActiveListingLimit((int) Auth::id(), $donation->id)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'listing_limit' => 'You have reached the maximum limit of 5 active donations. Please remove an existing donation before creating a new one',
                ]);
        }

        // A draft is still built from the same form as a real submission,
        // and shares the same mandatory fields — the only thing a draft is
        // actually exempt from is agreeing to the terms below, since that's
        // tied to the donation going live, not to describing it. Saving a
        // completely blank "draft" was never a meaningful state to allow.
        $rules = [
            'donation_title' => ['required', 'string', 'max:100'],
            'donation_description' => ['nullable', 'string'],
            'donation_funding' => ['required', 'in:yes,no'],
            'donation_payment' => ['nullable', 'required_if:donation_funding,yes', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'required_if:donation_funding,yes', 'numeric', 'gt:0'],
            'donation_method' => ['nullable', 'required_if:donation_funding,no', 'string', 'max:100'],
            // Also fixes a pre-existing gap: this field is marked mandatory
            // in the form ("Add details for your selected method *") for a
            // non-financial donation, but was never actually enforced here.
            'donation_notes' => ['nullable', 'required_if:donation_funding,no', 'string'],
            'donation_image_upload' => ['nullable', 'required_without:donation_image_default', 'image', 'max:5120'],
            'donation_image_default' => ['nullable', 'required_without:donation_image_upload', 'string', 'max:500'],
        ];

        if (! $isDraft) {
            $rules['donation_terms'] = ['accepted'];
        }

        $validated = $request->validate($rules, [
            'donation_image_upload.required_without' => 'Please upload an image or select one from the default gallery.',
            'donation_image_default.required_without' => 'Please upload an image or select one from the default gallery.',
            'donation_notes.required_if' => 'Please provide the details for your selected method.',
            'expected_cost.gt' => 'Please enter a cost greater than zero.',
            'expected_cost.required_if' => 'Expected cost is required.',
        ]);

        $image = $donation->image;
        if ($request->hasFile('donation_image_upload')) {
            $image = $this->storeUploadedDonationImage($request->file('donation_image_upload'));
        } elseif (!empty($validated['donation_image_default'])) {
            $defaultImage = $validated['donation_image_default'];
            if (filter_var($defaultImage, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($defaultImage);
                $defaultImage = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $defaultImage;
            }
            $image = $defaultImage;
        }

        $donation->fill([
            'title' => $validated['donation_title'] ?? null,
            'summary_title' => $validated['donation_title'] ?? null,
            'description' => $validated['donation_description'] ?? null,
            'image' => $image,
            'expected_cost' => $validated['expected_cost'] ?? null,
            'financial_assistance' => ($validated['donation_funding'] ?? null) === 'yes' ? ($validated['donation_payment'] ?? null) : null,
            'non_pay_option' => ($validated['donation_funding'] ?? null) === 'no' ? 1 : 0,
            'way_of_donation' => ($validated['donation_funding'] ?? null) === 'no' ? ($validated['donation_method'] ?? null) : null,
            'description_of_way' => ($validated['donation_funding'] ?? null) === 'no' ? ($validated['donation_notes'] ?? null) : null,
            'i_agree_decide' => $isDraft ? 0 : 1,
            'status' => $isDraft ? 0 : 1,
            'date_updated' => now(),
        ]);

        $donation->save();

        if (!$isDraft && (int) $donation->donation_email_status !== 1) {
            $this->sendMailQuietly(fn () => Mail::to($request->user()->email)->send(new DonationCreated($donation, $request->user())), 'DonationCreated');
            $donation->forceFill(['donation_email_status' => 1])->save();
        }

        if ($isDraft) {
            return redirect()
                ->route('donations.drafts')
                ->with('status', 'Your donation draft has been updated.');
        }

        // See WishController::update() for why: land back on the donation's
        // own page like a fresh submission does, carrying forward whatever
        // source/source_tab the edit form was opened with so the Back arrow
        // still points wherever the user actually started from.
        return redirect()
            ->route('donations.show', [
                'donation' => $donation->id,
                'source' => $request->input('source') ?: 'active',
                'source_tab' => $request->input('source_tab') ?: 'current-donations',
            ])
            ->with('status', 'Your donation has been updated.');
    }

    public function destroy(Request $request, int $donation): RedirectResponse
    {
        // Deletable while it's still a Draft (0) or Current (1), and also
        // once it's Completed (3, the "Granted" section) — same as any
        // other owned post can be cleaned up once it's done. Not while it's
        // In Progress/Accepted (2), since someone is actively mid-fulfillment
        // at that point.
        $donation = Donation::where('id', $donation)
            ->where('created_by', Auth::id())
            ->whereIn('status', [0, 1, 3])
            ->firstOrFail();

        $donation->delete();

        $source = (string) $request->input('source', '');
        $sourceTab = (string) $request->input('source_tab', '');

        if ($source === 'active') {
            $redirect = redirect()->route('wishes.active');

            if ($sourceTab !== '') {
                $redirect->withFragment($sourceTab);
            }

            return $redirect->with('status', 'Donation deleted.');
        }

        if ($source === 'my-wishes') {
            $parameters = [];
            if ($sourceTab !== '') {
                $parameters['tab'] = $sourceTab;
            }

            return redirect()
                ->route('my.wishes', $parameters)
                ->with('status', 'Donation deleted.');
        }

        return redirect()
            ->route('donations.drafts')
            ->with('status', 'Donation draft deleted.');
    }

    public function accept(Request $request, int $donation): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where('status', 1)
            ->firstOrFail();

        if ((int) $donation->created_by === (int) Auth::id()) {
            return redirect()
                ->route('donations.show', $donation->id)
                ->with('status', 'You cannot accept your own donation.');
        }

        // The agreement checkbox is required for both financial and
        // non-financial donations now — same disclaimer modal, same
        // condition, for either kind of acceptance.
        $request->validate([
            'non_financial_agreement' => ['accepted'],
        ], [
            'non_financial_agreement.accepted' => 'You must agree to the conditions before accepting this donation.',
        ]);

        $donation->forceFill([
            'accepted_by' => Auth::id(),
            'accepted_at' => now(),
            'status' => 2,
            'process_status' => 1,
            'date_updated' => now(),
        ])->save();

        $acceptor = $request->user();
        $donor = User::find($donation->created_by);

        if ($acceptor && $donor) {
            if ((int) $donation->non_pay_option !== 1) {
                $this->sendMailQuietly(fn () => Mail::to($acceptor->email)->send(new DonationAcceptedFinancial($donation, $acceptor, $donor)), 'DonationAcceptedFinancial');
            } else {
                $this->sendMailQuietly(fn () => Mail::to($acceptor->email)->send(new DonationAcceptedNonFinancial($donation, $acceptor, $donor)), 'DonationAcceptedNonFinancial');
            }

            $this->sendMailQuietly(fn () => Mail::to($donor->email)->send(new DonationAcceptedCreator($donation, $donor, $acceptor)), 'DonationAcceptedCreator');
        }

        // Carries forward whatever source/source_tab the Accept form was
        // submitted with (see donation-preview.blade.php), so the Back
        // arrow returns to wherever the user actually started — e.g. the
        // Active Wishes & Donations page's Current Donations tab — instead
        // of always falling back to its default tab.
        return redirect()
            ->route('donations.show', [
                'donation' => $donation->id,
                'source' => $request->input('source') ?: 'active',
                'source_tab' => $request->input('source_tab') ?: 'current-donations',
            ])
            ->with('status', 'Donation accepted successfully. It is now in progress.');
    }

    public function complete(Request $request, int $donation): RedirectResponse
    {
        $donation = Donation::where('id', $donation)
            ->where('created_by', Auth::id())
            ->where('status', 2)
            ->firstOrFail();

        $donation->forceFill([
            'completed_by' => Auth::id(),
            'completed_at' => now(),
            'status' => 3,
            'process_status' => 2,
            'date_updated' => now(),
        ])->save();

        $acceptor = $donation->accepted_by ? User::find($donation->accepted_by) : null;
        if ($acceptor) {
            $this->sendMailQuietly(fn () => Mail::to($acceptor->email)->send(new DonationAcceptedCompleted($donation, $acceptor)), 'DonationAcceptedCompleted');
        }

        $donor = Auth::user();
        if ($donor) {
            $this->sendMailQuietly(fn () => Mail::to($donor->email)->send(new DonationCreatorCompleted($donation, $donor)), 'DonationCreatorCompleted');
        }

        // Carries forward whatever source/source_tab the Complete Donation
        // form was submitted with (see donation-preview.blade.php) — this
        // action is only ever available on an In Progress donation, so
        // that's the sensible fallback tab if none was given.
        return redirect()
            ->route('donations.show', [
                'donation' => $donation->id,
                'source' => $request->input('source') ?: 'active',
                'source_tab' => $request->input('source_tab') ?: 'in-progress',
            ])
            ->with('status', 'Donation completed successfully.');
    }

    private function hasReachedActiveListingLimit(int $userId, ?int $excludeDonationId = null): bool
    {
        // Only donations still in the "Active" state (status = 1) count
        // toward the limit. Once a donation is granted (2) or completed (3)
        // it's In Progress / completed and no longer counts.
        return Donation::where('created_by', $userId)
            ->where('status', 1)
            ->when($excludeDonationId, fn ($query) => $query->where('id', '!=', $excludeDonationId))
            ->count()
            >= self::MAX_ACTIVE_LISTINGS;
    }
}
