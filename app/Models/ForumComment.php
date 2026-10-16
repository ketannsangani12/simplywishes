<?php

namespace App\Models;

use App\Models\Scopes\ExcludeBlockedUsersScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class ForumComment extends Model
{
    protected static function booted(): void
    {
        // Blocking is mutual and total: neither side sees the other's
        // comments or replies anywhere, and can't reply to, like, edit or
        // report them (lookups by id 404 for a blocked pair).
        static::addGlobalScope(new ExcludeBlockedUsersScope('user_id'));
    }

    protected $table = 'forum_comments';

    protected $fillable = [
        'forum_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function forum(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'forum_id', 'e_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumCommentLike::class, 'comment_id');
    }
}
