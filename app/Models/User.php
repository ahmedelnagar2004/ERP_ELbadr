<?php

namespace App\Models;

use App\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Authorizable, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $table = 'users';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'status',
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

    /**
     * Get the status as enum
     */
    public function getStatusEnumAttribute(): UserStatus
    {
        return $this->status == 1 ? UserStatus::Active : UserStatus::Inactive;
    }

    /**
     * Set the status from enum
     */
    public function setStatusAttribute($value)
    {
        if ($value instanceof UserStatus) {
            $this->attributes['status'] = $value->value;
        } else {
            $this->attributes['status'] = $value === 'active' || $value === 1 || $value === 'نشط' ? 1 : 0;
        }
    }
}
