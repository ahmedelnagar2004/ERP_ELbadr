<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    protected $table = 'clients';

    public $timestamps = true;

    use SoftDeletes, HasApiTokens;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'email', 'phone', 'address', 'balance', 'status', 'password'];

    protected function casts(): array
    {
        return [
            'status' => ClientStatus::class,
        ];
    }

    protected $appends = ['name'];

    /**
     * Get the client's name.
     *
     * @return string
     */
    public function getNameAttribute($value = null)
    {
        return $value ?? $this->attributes['name'] ?? null;
    }

    /**
     * Get the client's name for display.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->name.($this->phone ? ' - '.$this->phone : '');
    }

    /**
     * Get the client's account transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ClientAccountTransaction::class)->latest();
    }

    /**
     * Get the sales associated with the client.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class)->latest();
    }
}
