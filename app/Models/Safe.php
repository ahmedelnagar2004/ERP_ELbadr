<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Safe extends Model 
{

    protected $table = 'safes';
    public $timestamps = true;
    protected $fillable = array('name', 'balance', 'status', 'description');

}