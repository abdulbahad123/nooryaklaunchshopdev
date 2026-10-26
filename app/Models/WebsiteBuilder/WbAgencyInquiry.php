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

    public static function ensureTableExists(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('wb_agency_inquiries')) {
                \Illuminate\Support\Facades\Schema::create('wb_agency_inquiries', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('customer_id')->nullable();
                    $table->string('name')->nullable();
                    $table->string('email')->nullable();
                    $table->string('phone')->nullable();
                    $table->string('subject')->nullable();
                    $table->text('message')->nullable();
                    $table->boolean('is_read')->default(false);
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            // fail-safe
        }
    }
}
