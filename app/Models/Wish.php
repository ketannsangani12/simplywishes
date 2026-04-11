<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    protected $table = 'wishes';
    protected $primaryKey = 'w_id';
    public $timestamps = false;

    protected $fillable = [
        'wished_by',
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
        'wish_progress_status',
        'created_at',
        'wish_email_status',
    ];
}
