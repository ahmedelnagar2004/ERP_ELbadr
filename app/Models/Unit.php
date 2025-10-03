<?php

namespace App\Models;

use App\UnitStatus;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    public $timestamps = true;

    protected $fillable = ['name', 'status'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the status as enum
     */
    public function getStatusEnumAttribute(): UnitStatus
    {
        return $this->status == 1 ? UnitStatus::Active : UnitStatus::Inactive;
    }

    /**
     * Set the status from enum
     */
    public function setStatusEnumAttribute(UnitStatus $status): void
    {
        $this->status = $status->value;
    }
}
