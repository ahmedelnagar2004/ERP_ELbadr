<?php

namespace App\Models;

use App\Enums\SaleStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model 
{

    protected $table = 'sales';
    public $timestamps = true;
    protected $fillable = array('client_id', 'user_id', 'safe_id', 'total', 'type', 'discount', 'discount_type', 'shipping_cost', 'net_amount', 'paid_amount', 'remaining_amount', 'invoice_number', 'payment_type', 'order_date', 'notes');
    
    /**
     * Scope a query to filter sales by type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $type 0 for sales, 1 for returns, null for all
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterType($query, $type = null)
    {
        if ($type !== null) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function safeTransactions()
    {
        return $this->morphMany('App\Models\SafeTransaction', 'reference');
    }
    protected $casts = [
        'type' => SaleStatusEnum::class,
    ];

    public function safe()
    {
        return $this->belongsTo('App\Models\Safe');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function items()
    {
        return $this->morphToMany('App\Models\Item', 'itemable')
            ->withPivot('unit_price', 'quantity', 'total_price')
            ->withTimestamps();
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}