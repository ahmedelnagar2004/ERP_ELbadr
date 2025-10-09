<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'balance',
        'status',
    ];

    // ✅ تحويل تلقائي لحقل status إلى Enum
    protected $casts = [
        'status' => ClientStatus::class,
    ];

    // ✅ الاسم الكامل
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->phone ? ' - ' . $this->phone : '');
    }

    // ✅ getter لاسم العميل (اختياري)
    public function getNameAttribute($value): ?string
    {
        return $value ?? null;
    }
    

    // (اختياري) اسم واضح للوصول السهل من الـ view
    public function getStatusEnumAttribute()
    {
        return $this->status instanceof ClientStatus
            ? $this->status
            : ClientStatus::tryFrom((int) $this->status);
    }
}
