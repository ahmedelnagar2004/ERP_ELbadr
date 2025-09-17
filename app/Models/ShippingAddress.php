<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model 
{

    protected $table = 'shipping_addresses';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'phone', 'address');

}