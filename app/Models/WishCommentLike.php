<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishCommentLike extends Model
{
    protected $table = 'wish_comment_likes';
    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'created_at',
    ];
}
