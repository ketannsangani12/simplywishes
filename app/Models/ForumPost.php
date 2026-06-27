<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class ForumPost extends Model
{
    protected $table = 'forum';
    protected $primaryKey = 'e_id';

    protected $fillable = [
        'e_title',
        'e_text',
        'description',
        'e_image',
        'article_image',
        'featured_video_url',
        'status',
        'is_video_only',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'forum_id', 'e_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumLike::class, 'forum_id', 'e_id');
    }
}
