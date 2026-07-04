<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Wish;

class HappyStory extends Model
{
    protected $table = 'happy_stories';
    protected $primaryKey = 'hs_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'wish_id',
        'story_text',
        'story_image',
        'status',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wish(): BelongsTo
    {
        return $this->belongsTo(Wish::class, 'wish_id', 'w_id');
    }
}
