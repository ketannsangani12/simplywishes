<?php

namespace App\Http\Controllers;

use App\Mail\DonationCreated;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonationController extends Controller
{
    private function storeUploadedDonationImage(\Illuminate\Http\UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = Str::uuid()->toString() . '.' . $extension;

        $candidateDirectories = [
            public_path('uploads/donations'),
            base_path('../public_html/uploads/donations'),
        ];

        $uploadDirectory = null;
        foreach ($candidateDirectories as $directory) {
            $parentDirectory = dirname($directory);
            if (is_dir($directory) || is_dir($parentDirectory)) {
                $uploadDirectory = $directory;
                break;
            }
        }

        $uploadDirectory ??= public_path('uploads/donations');

        File::ensureDirectoryExists($uploadDirectory);
        $file->move($uploadDirectory, $filename);

        return 'uploads/donations/' . $filename;
    }

    public function create(): View
    {
        return view('users.user.create-donation');
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
        $donation = Donation::where('id', $donation)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $creator = \App\Models\User::where('id', $donation->created_by)->first();

        $userId = Auth::id();
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

        return view('users.user.donation-preview', compact('donation', 'creator', 'favDonationIds', 'likeDonationIds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $isDraft = $request->input('action') === 'draft';

        $rules = $isDraft ? [
            'donation_title' => ['nullable', 'string', 'max:100'],
            'donation_description' => ['nullable', 'string'],
            'donation_funding' => ['nullable', 'in:yes,no'],
            'donation_payment' => ['nullable', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'numeric', 'min:0'],
            'donation_method' => ['nullable', 'string', 'max:100'],
            'donation_notes' => ['nullable', 'string'],
            'donation_image_upload' => ['nullable', 'image', 'max:5120'],
            'donation_image_default' => ['nullable', 'string', 'max:500'],
        ] : [
            'donation_title' => ['required', 'string', 'max:100'],
            'donation_description' => ['nullable', 'string'],
            'donation_funding' => ['required', 'in:yes,no'],
            'donation_payment' => ['nullable', 'required_if:donation_funding,yes', 'string', 'max:255'],
            'expected_cost' => ['nullable', 'required_if:donation_funding,yes', 'numeric', 'min:0'],
            'donation_method' => ['nullable', 'required_if:donation_funding,no', 'string', 'max:100'],
            'donation_notes' => ['nullable', 'string'],
            'donation_image_upload' => ['nullable', 'image', 'max:5120'],
            'donation_image_default' => ['nullable', 'string', 'max:500'],
            'donation_terms' => ['accepted'],
        ];

        $validated = $request->validate($rules);

        $user = $request->user();

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
            Mail::to($user->email)->send(new DonationCreated($donation, $user));
            $donation->forceFill(['donation_email_status' => 1])->save();
        }

        return redirect()
            ->route('donations.create')
            ->with('status', $isDraft ? 'Your donation draft has been saved.' : 'Your donation has been created successfully.');
    }
}
