<?php

namespace App\Models;

use App\SafeStatus;
use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    protected $table = 'safes';

    protected $fillable = [
        'name',
        'type',
        'balance',
        'currency',
        'status',
        'description',
        'branch_id',
        'account_number'
    ];

    protected $attributes = [
        'type' => 1,
        'balance' => 0,
        'status' => SafeStatus::ACTIVE,
        'currency' => 'EGP'
    ];

    protected $casts = [
        'status' => SafeStatus::class,
        'balance' => 'decimal:2'
    ];

    public $timestamps = true;

    /**
     * التحقق من أن الخزنة نشطة
     */
    public function isActive(): bool
    {
        return $this->status === SafeStatus::ACTIVE;
    }

    /**
     * التحقق من أن الخزنة غير نشطة
     */
    public function isInactive(): bool
    {
        return $this->status === SafeStatus::INACTIVE;
    }

    /**
     * تنشيط الخزنة
     */
    public function activate(): void
    {
        $this->update(['status' => SafeStatus::ACTIVE]);
    }

    /**
     * إلغاء تنشيط الخزنة
     */
    public function deactivate(): void
    {
        $this->update(['status' => SafeStatus::INACTIVE]);
    }

    /**
     * الحصول على تسمية الحالة
     */
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

    /**
     * الحصول على لون الحالة للـ CSS
     */
    public function getStatusColor(): string
    {
        return $this->status->color();
    }

    /**
     * الحصول على أيقونة الحالة
     */
    public function getStatusIcon(): string
    {
        return $this->status->icon();
    }

    /**
     * نطاق الخزنات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('status', SafeStatus::ACTIVE);
    }

    /**
     * نطاق الخزنات غير النشطة فقط
     */
    public function scopeInactive($query)
    {
        return $query->where('status', SafeStatus::INACTIVE);
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\SafeTransaction');
    }

    public function returns()
    {
        return $this->hasMany('App\Models\Return');
    }
}
