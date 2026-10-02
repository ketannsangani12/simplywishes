<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Orders a tab that mixes wishes and donations (In Progress, Granted…) by
 * when each item moved into that tab, newest first, so an item that was
 * just granted/accepted/fulfilled/completed lands at the top of the list.
 *
 * Wish ids and donation ids are separate sequences, so sorting the merged
 * list by id (as before) put new moves in the middle of the list.
 */
class TabOrdering
{
    /**
     * @param  iterable  $wishes      Wish models
     * @param  string    $wishColumn  when the wish entered this tab (e.g. granted_date)
     * @param  iterable  $donations   Donation models
     * @param  string    $donationColumn  when the donation entered this tab (e.g. accepted_at)
     * @return Collection<int, object{type: string, model: mixed, moved_at: int}>
     */
    public static function merge(iterable $wishes, string $wishColumn, iterable $donations, string $donationColumn): Collection
    {
        $wrap = fn (string $type, string $column) => fn ($model) => (object) [
            'type' => $type,
            'model' => $model,
            'moved_at' => self::timestamp($model->{$column}) ?? self::timestamp($model->created_at) ?? 0,
        ];

        return collect($wishes)->map($wrap('wish', $wishColumn))
            ->concat(collect($donations)->map($wrap('donation', $donationColumn)))
            ->sortByDesc('moved_at')
            ->values();
    }

    /** Unix time for a Carbon/DateTime or a stored date string; null if missing or unparseable. */
    public static function timestamp($value): ?int
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }

        if (! is_string($value) || trim($value) === '' || str_starts_with($value, '0000-00-00')) {
            return null;
        }

        $time = strtotime($value);

        return $time === false ? null : $time;
    }
}
