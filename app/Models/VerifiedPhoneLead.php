<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifiedPhoneLead extends Model
{
    use SoftDeletes;

    protected $table = 'verified_phone_leads';

    protected $fillable = [
        'name',
        'phone',
        'country_code',
        'email',
        'purchased',
        'status',
        'status_date',
        'is_verified',
        'otp_sent_at',
    ];

    protected $casts = [
        'purchased'   => 'boolean',
        'is_verified' => 'boolean',
        'otp_sent_at' => 'datetime',
        'status_date' => 'datetime',
    ];
}
