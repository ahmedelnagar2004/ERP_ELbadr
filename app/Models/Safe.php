<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Safe extends Model 
{

    protected $table = 'safes';
    protected $fillable = array('name', 'type', 'balance', 'currency', 'status', 'description', 'branch_id', 'account_number');
    public $timestamps = true;
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\SafeTransaction');
    }

    public function returns()
    {
        return $this->hasMany('App\Models\Return');
    }
}