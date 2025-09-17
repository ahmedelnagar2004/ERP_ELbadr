<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model 
{

    protected $table = 'sale_items';
    public $timestamps = true;
    protected $fillable = array('quantity', 'unit_price', 'total_price');

}