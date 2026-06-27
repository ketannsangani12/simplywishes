<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    protected $table = 'forum_likes';
    public $timestamps = false;

    protected $fillable = [
        'forum_id',
        'user_id',
        'created_at',
    ];
}
