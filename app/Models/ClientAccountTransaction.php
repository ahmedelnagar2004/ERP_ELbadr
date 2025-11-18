<?php

namespace App\Models;
use App\Enums\ClientAccountTransactionTypeEnum;
use App\Models\Client;
use App\Models\Safe;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ClientAccountTransaction extends Model
{
    protected $table = 'client_account_transactions';

    protected $fillable = [
        'client_id',
        'safe_id',
        'amount',
        'type',
        'reference_type',
        'reference_id',
        'user_id',
        'balance_after',
        'description',
    ];

    protected $casts = [
        'type' => ClientAccountTransactionTypeEnum::class,
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
