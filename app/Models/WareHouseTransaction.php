<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Warehouse;
use App\Models\Order;
use App\Models\User;
use App\Enums\WareHouseTransactions;
use Illuminate\Database\Eloquent\Model;

class WareHouseTransaction extends Model
{
    protected $table = 'warehouse_transactions';

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'reference_id',
        'user_id',
        'reference_type',
        'type',
        'description',
        'balance_after',
        'quantity',
    ];  

    protected $casts = [
        'type' => WareHouseTransactions::class,
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function reference()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}