<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itemables extends Model 
{

    protected $table = 'itemables';
    public $timestamps = true;
    protected $fillable = array('quantity', 'unit_price', 'total_price');

}