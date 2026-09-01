<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wish extends Model
{
    protected $table = 'wishes';
    protected $primaryKey = 'w_id';
    public $timestamps = false;

    protected $fillable = [
        'wished_by',
        'granted_by',
        'granted_date',
        'wish_title',
        'summary_title',
        'wish_description',
        'primary_image',
        'expected_cost',
        'expected_date',
        'financial_assistance',
        'financial_assistance_other',
        'non_pay_option',
        'way_of_wish',
        'description_of_way',
        'show_mail_status',
        'show_mail',
        'i_agree_decide',
        'i_agree_decide2',
        'wish_status',
        'process_status',
        'process_granted_by',
        'process_granted_date',
        'fulfilled_by',
        'fulfilled_date',
        'wish_progress_status',
        'created_at',
        'wish_email_status',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wished_by');
    }

    /**
     * URL for primary_image, routed through WishController rather than a
     * plain asset()/static path — see WishController::defaultImage() for why.
     */
    public function imageUrl(): ?string
    {
        $path = trim((string) $this->primary_image);
        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'uploads/wishes/')) {
            return route('wishes.upload', ['filename' => basename($path)], false);
        }

        if (str_starts_with($path, 'images/wishes-default/')) {
            return route('wishes.default-image', ['filename' => basename($path)], false);
        }

        return '/' . $path;
    }
}
