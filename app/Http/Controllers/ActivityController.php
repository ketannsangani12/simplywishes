<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Donation;
use App\Models\HappyStory;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'wish_id' => ['nullable', 'integer'],
            'donation_id' => ['nullable', 'integer'],
            'happy_story_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:fav,like'],
        ]);

        if (empty($validated['wish_id']) && empty($validated['donation_id']) && empty($validated['happy_story_id'])) {
            return response()->json(['ok' => false], 422);
        }
        if (collect([$validated['wish_id'] ?? null, $validated['donation_id'] ?? null, $validated['happy_story_id'] ?? null])->filter()->count() !== 1) {
            return response()->json(['ok' => false], 422);
        }

        if (!empty($validated['wish_id'])) {
            Wish::where('w_id', $validated['wish_id'])->firstOrFail();
        } elseif (!empty($validated['donation_id'])) {
            Donation::where('id', $validated['donation_id'])->firstOrFail();
        } else {
            HappyStory::where('hs_id', $validated['happy_story_id'])->firstOrFail();
        }

        $activity = Activity::firstOrCreate([
            'user_id' => $request->user()->id,
            'wish_id' => $validated['wish_id'] ?? null,
            'donation_id' => $validated['donation_id'] ?? null,
            'happy_story_id' => $validated['happy_story_id'] ?? null,
            'type' => $validated['type'],
        ]);

        return response()->json([
            'ok' => true,
            'id' => $activity->id,
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'wish_id' => ['nullable', 'integer'],
            'donation_id' => ['nullable', 'integer'],
            'happy_story_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:fav,like'],
        ]);

        if (empty($validated['wish_id']) && empty($validated['donation_id']) && empty($validated['happy_story_id'])) {
            return response()->json(['ok' => false], 422);
        }
        if (collect([$validated['wish_id'] ?? null, $validated['donation_id'] ?? null, $validated['happy_story_id'] ?? null])->filter()->count() !== 1) {
            return response()->json(['ok' => false], 422);
        }

        Activity::where('user_id', $request->user()->id)
            ->when(!empty($validated['wish_id']), fn ($q) => $q->where('wish_id', $validated['wish_id']))
            ->when(!empty($validated['donation_id']), fn ($q) => $q->where('donation_id', $validated['donation_id']))
            ->when(!empty($validated['happy_story_id']), fn ($q) => $q->where('happy_story_id', $validated['happy_story_id']))
            ->where('type', $validated['type'])
            ->delete();

        return response()->json([
            'ok' => true,
        ]);
    }
}
