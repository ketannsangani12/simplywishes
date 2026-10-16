<?php

namespace App\Models;

use App\Models\Scopes\ExcludeBlockedUsersScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationComment extends Model
{
    protected static function booted(): void
    {
        // Blocking is mutual and total: neither side sees the other's
        // comments or replies anywhere, and can't reply to, like, edit or
        // report them (lookups by id 404 for a blocked pair).
        static::addGlobalScope(new ExcludeBlockedUsersScope('user_id'));
    }

    protected $table = 'donation_comments';

    protected $fillable = [
        'donation_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(DonationCommentLike::class, 'comment_id');
    }
}
