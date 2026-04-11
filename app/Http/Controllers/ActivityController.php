<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
            'type' => ['required', 'in:fav,like'],
        ]);

        if (empty($validated['wish_id']) && empty($validated['donation_id'])) {
            return response()->json(['ok' => false], 422);
        }
        if (!empty($validated['wish_id']) && !empty($validated['donation_id'])) {
            return response()->json(['ok' => false], 422);
        }

        if (!empty($validated['wish_id'])) {
            Wish::where('w_id', $validated['wish_id'])->firstOrFail();
        } else {
            \App\Models\Donation::where('id', $validated['donation_id'])->firstOrFail();
        }

        $activity = Activity::firstOrCreate([
            'user_id' => $request->user()->id,
            'wish_id' => $validated['wish_id'] ?? null,
            'donation_id' => $validated['donation_id'] ?? null,
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
            'type' => ['required', 'in:fav,like'],
        ]);

        if (empty($validated['wish_id']) && empty($validated['donation_id'])) {
            return response()->json(['ok' => false], 422);
        }
        if (!empty($validated['wish_id']) && !empty($validated['donation_id'])) {
            return response()->json(['ok' => false], 422);
        }

        Activity::where('user_id', $request->user()->id)
            ->when(!empty($validated['wish_id']), fn ($q) => $q->where('wish_id', $validated['wish_id']))
            ->when(!empty($validated['donation_id']), fn ($q) => $q->where('donation_id', $validated['donation_id']))
            ->where('type', $validated['type'])
            ->delete();

        return response()->json([
            'ok' => true,
        ]);
    }
}
