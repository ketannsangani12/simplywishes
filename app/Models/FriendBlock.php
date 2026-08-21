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
}
