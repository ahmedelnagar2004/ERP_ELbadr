<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes, HasRoles, Authorizable , Notifiable;

    use HasFactory, Notifiable, SoftDeletes, HasRoles, Authorizable;

    protected $table = 'users';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'username', 
        'password', 
        'full_name', 
        'email', 
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name ?? $this->username ?? 'بدون اسم';
    }
}