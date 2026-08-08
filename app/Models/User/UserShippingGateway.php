<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShippingGateway extends Model
{
    use HasFactory;
    
    protected $fillable = ['status', 'user_id', 'details', 'keyword', 'subtitle', 'name', 'information'];
    protected $table = 'user_shipping_gateways';

    public function convertAutoData()
    {
        return json_decode($this->information, true);
    }
}
