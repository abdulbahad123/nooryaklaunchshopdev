<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbAgencyInquiry extends Model
{
    use HasFactory;

    protected $table = 'wb_agency_inquiries';

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
