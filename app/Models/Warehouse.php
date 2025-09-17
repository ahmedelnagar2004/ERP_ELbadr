<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model 
{

    protected $table = 'warehouses';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'status');

}