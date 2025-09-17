<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model 
{

    protected $table = 'order_items';
    public $timestamps = true;
    protected $fillable = array('unit_price', 'quantity', 'total_price');
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

}