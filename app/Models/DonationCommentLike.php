<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationCommentLike extends Model
{
    protected $table = 'donation_comment_likes';
    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'created_at',
    ];
}
