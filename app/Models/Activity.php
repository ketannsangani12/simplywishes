<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'wish_id',
        'donation_id',
        'type',
    ];
}
