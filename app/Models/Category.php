<?php

namespace App\Models;

use App\CategoryStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = true;

    protected $fillable = ['name', 'status'];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function photo()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('usage', 'category_photo');
    }

    /**
     * Get the status as enum
     */
    public function getStatusEnumAttribute(): CategoryStatus
    {
        return $this->status == 1 ? CategoryStatus::Active : CategoryStatus::Inactive;
    }

    /**
     * Set the status from enum
     */
    public function setStatusEnumAttribute(CategoryStatus $status): void
    {
        $this->status = $status->value;
    }
}
