<?php

namespace App\Http\Controllers;

use App\Mail\WishCreated;
use App\Models\Donation;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WishController extends Controller
{
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
            $file = $request->file('wish_image_upload');
            $uploadDir = public_path('uploads/wishes');
            File::ensureDirectoryExists($uploadDir);
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $primaryImage = 'uploads/wishes/' . $filename;
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
        $wishes = Wish::where('wish_status', 1)
            ->orderByDesc('w_id')
            ->get();

        $grantedWishes = Wish::where('wish_status', 1)
            ->where('wish_progress_status', 2)
            ->orderByDesc('w_id')
            ->get();

        $inProgressWishes = Wish::where('wish_status', 1)
            ->where('wish_progress_status', 1)
            ->orderByDesc('w_id')
            ->get();

        $donations = Donation::where('status', 1)
            ->orderByDesc('id')
            ->get();

        $userIds = $wishes->pluck('wished_by')
            ->merge($donations->pluck('created_by'))
            ->unique()
            ->filter()
            ->all();
        $userMap = User::whereIn('id', $userIds)->get()->keyBy('id');

        $userId = Auth::id();
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
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->firstOrFail();

        $creator = \App\Models\User::where('id', $wish->wished_by)->first();

        return view('users.user.wish-preview', compact('wish', 'creator'));
    }

    public function edit(int $wish): View
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
            ->firstOrFail();

        return view('users.user.create-wish', compact('wish'));
    }

    public function update(Request $request, int $wish): RedirectResponse
    {
        $wish = Wish::where('w_id', $wish)
            ->where('wished_by', Auth::id())
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
            $file = $request->file('wish_image_upload');
            $uploadDir = public_path('images/wishes-upload');
            File::ensureDirectoryExists($uploadDir);
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $primaryImage = 'images/wishes-upload/' . $filename;
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
            ->firstOrFail();

        $wish->delete();

        return redirect()
            ->route('wishes.drafts')
            ->with('status', 'Wish draft deleted.');
    }
}
