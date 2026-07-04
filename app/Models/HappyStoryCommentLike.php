<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HappyStoryCommentLike extends Model
{
    protected $table = 'happy_story_comment_likes';

    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'created_at',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(HappyStoryComment::class, 'comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
