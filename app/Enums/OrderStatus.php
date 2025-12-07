<?php

namespace App\Enums;

enum OrderStatus: int
{
    case CONFIRMED = 1;
    case PROCESSING = 2;
    case SHIPPED = 3;
    case DELIVERED = 4;

    public function label(): string
    {
        return match($this) {
            self::CONFIRMED => 'تم التأكيد',
            self::PROCESSING => 'قيد التجهيز',
            self::SHIPPED => 'تم الشحن',
            self::DELIVERED => 'تم التسليم',
        };
    }
}
