<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendBlock extends Model
{
    protected $table = 'friend_blocks';
    public $timestamps = false;

    protected $fillable = [
        'blocker_id',
        'blocked_id',
        'created_at',
    ];

    public static function existsBetween(int $userId, int $otherUserId): bool
    {
        return static::where('blocker_id', $userId)->where('blocked_id', $otherUserId)->exists()
            || static::where('blocker_id', $otherUserId)->where('blocked_id', $userId)->exists();
    }

    /**
     * Every user id that's blocked with the given user, in either direction —
     * used everywhere blocking needs to hide people/content from each other
     * (content feeds, search, the wishers/granters/donors directory, the
     * inbox's contact list) without caring which side did the blocking.
     */
    public static function blockedUserIdsFor(int $userId): \Illuminate\Support\Collection
    {
        return static::where('blocker_id', $userId)->pluck('blocked_id')
            ->merge(static::where('blocked_id', $userId)->pluck('blocker_id'))
            ->unique()
            ->values();
    }
}
