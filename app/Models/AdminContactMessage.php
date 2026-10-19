<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AdminContactMessage extends Model
{
    protected $table = 'admin_contact_messages';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'describe_issues',
        'subject',
        'message',
        'status'
    ];

    /**
     * Defensive check: automatically create table if it does not exist yet.
     */
    public static function ensureTableExists()
    {
        try {
            if (!Schema::hasTable('admin_contact_messages')) {
                Schema::create('admin_contact_messages', function (Blueprint $table) {
                    $table->id();
                    $table->string('name')->nullable();
                    $table->string('email')->nullable();
                    $table->string('phone', 100)->nullable();
                    $table->string('describe_issues')->nullable();
                    $table->string('subject')->nullable();
                    $table->text('message')->nullable();
                    $table->string('status', 50)->default('unread');
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            \Log::warning('ensureTableExists admin_contact_messages: ' . $e->getMessage());
        }
    }
}