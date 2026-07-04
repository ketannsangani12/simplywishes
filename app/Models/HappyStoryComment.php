<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HappyStoryComment extends Model
{
    protected $table = 'happy_story_comments';

    protected $fillable = [
        'happy_story_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(HappyStory::class, 'happy_story_id', 'hs_id');
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
        return $this->hasMany(HappyStoryCommentLike::class, 'comment_id');
    }
}
