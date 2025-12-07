<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = [
        'client_id',
        'item_id',
        'price',
        'total_price',
        'quantity',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
