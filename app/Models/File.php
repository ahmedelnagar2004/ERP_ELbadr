<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model 
{

    protected $table = 'files';
    public $timestamps = true;
    protected $fillable = [
        'usage',
        'path',
        'ext',
        'filename',
        'size',
        'mime_type',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
}
