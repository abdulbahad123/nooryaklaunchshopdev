<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $table = 'package_features';

    protected $fillable = [
        'name',
        'keyword',
        'type',
        'limit_key',
        'serial_number'
    ];
}
