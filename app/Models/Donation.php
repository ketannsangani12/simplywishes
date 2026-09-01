<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $table = 'donations';
    public $timestamps = false;

    protected $fillable = [
        'created_by',
        'accepted_by',
        'accepted_at',
        'completed_by',
        'completed_at',
        'title',
        'summary_title',
        'description',
        'image',
        'expected_cost',
        'expected_date',
        'non_pay_option',
        'financial_assistance',
        'financial_assistance_other',
        'way_of_donation',
        'description_of_way',
        'show_mail_status',
        'show_mail',
        'i_agree_decide',
        'i_agree_decide2',
        'process_status',
        'status',
        'donation_email_status',
        'created_at',
        'date_updated',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * URL for image, routed through DonationController rather than a plain
     * asset()/static path — see WishController::defaultImage() for why.
     */
    public function imageUrl(): ?string
    {
        $path = trim((string) $this->image);
        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'uploads/donations/')) {
            return route('donations.upload', ['filename' => basename($path)], false);
        }

        if (str_starts_with($path, 'images/wishes-default/')) {
            return route('donations.default-image', ['filename' => basename($path)], false);
        }

        return '/' . $path;
    }
}
