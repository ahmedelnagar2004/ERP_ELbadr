<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeTransaction extends Model
{
    protected $table = 'safe_transactions';

    public $timestamps = true;

    protected $fillable = ['type', 'amount', 'description', 'balance_after', 'reference_type','reference_id','safe_id', 'user_id'];

    public function safe()
    {
        return $this->belongsTo('App\Models\Safe');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reference(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
