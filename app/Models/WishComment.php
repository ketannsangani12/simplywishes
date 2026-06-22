<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WishComment extends Model
{
    protected $table = 'wish_comments';

    protected $fillable = [
        'wish_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wish(): BelongsTo
    {
        return $this->belongsTo(Wish::class, 'wish_id', 'w_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(WishCommentLike::class, 'comment_id');
    }
}
