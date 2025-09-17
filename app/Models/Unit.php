<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model 
{

    protected $table = 'units';
    public $timestamps = true;
    protected $fillable = ['name', 'status'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
