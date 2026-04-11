<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations';
    public $timestamps = false;

    protected $fillable = [
        'created_by',
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
    ];
}
