<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $table = 'friend_requests';
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'created_at',
        'responded_at',
    ];
}
