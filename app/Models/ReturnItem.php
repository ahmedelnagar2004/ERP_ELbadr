<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model 
{

    protected $table = 'return_items';
    public $timestamps = true;
    protected $fillable = array('quantity', 'unit_price');

}