<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'name',
        'permissions',
    ];

    public function staff()
    {
        return $this->hasMany(UserStaff::class, 'role_id');
    }
}
