<?php

namespace App\Models;

use App\CategoryStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{
    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = ['name', 'status'];

    protected function casts(): array
    {
        return [
            'status' => CategoryStatus::class,
        ];
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function photo()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('usage','category_photo');
    }
}

