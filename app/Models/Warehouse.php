<?php

namespace App\Models;

use App\Models\Item;
use App\Models\WareHouseTransaction;
use Illuminate\Database\Eloquent\Model;

use App\Enums\WarehouseStatus;

class Warehouse extends Model 
{

    protected $table = 'warehouses';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'status');

    protected function casts(): array
    {
        return [
            'status' => WarehouseStatus::class,
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'warehouse_id');
    }

    public function wareHouseTransaction()
    {
        return $this->hasMany(WareHouseTransaction::class, 'warehouse_id');
    }

    public function transactions()
    {
        return $this->hasMany(WareHouseTransaction::class, 'warehouse_id');
    }
}



