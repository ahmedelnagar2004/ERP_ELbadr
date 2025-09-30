<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('status', 'payment_method', 'price', 'shipping_cost', 'total_price');

    public function items()
    {
        return $this->belongsToMany('App\Models\Item', 'order_items')->withPivot('unit_price','quantity','total_price');

        // return $this->hasMany('App\Models\OrderItem');
        
    }

}