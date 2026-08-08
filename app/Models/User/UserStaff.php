<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserStaff extends Authenticatable
{
    use HasFactory;

    protected $table = 'user_staff';

    protected $fillable = [
        'user_id',
        'role_id',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'phone',
        'image',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function merchant()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
