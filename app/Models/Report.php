<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $table = 'reports';
    public $timestamps = false;

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'status',
        'created_at',
        'resolved_at',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
