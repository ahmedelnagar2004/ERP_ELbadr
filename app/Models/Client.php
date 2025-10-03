<?php

namespace App\Models;

use App\ClientStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    protected $table = 'clients';

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'email', 'phone', 'address', 'balance', 'status'];

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
     * Get the status as enum
     */
    public function getStatusEnumAttribute(): ClientStatus
    {
        return $this->status == 1 ? ClientStatus::Active : ClientStatus::Inactive;
    }

    /**
     * Set the status from enum
     */
    public function setStatusEnumAttribute(ClientStatus $status): void
    {
        $this->status = $status->value;
    }
}
