<?php

namespace App\Models;
use App\UnitStatus;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model 
{

    protected $table = 'units';
    public $timestamps = true;
    protected $fillable = ['name', 'status'];

    protected function casts(): array
    {
        return [
            'status' => UnitStatus::class,
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}


