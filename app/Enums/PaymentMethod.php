<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case CASH = 1;
    case COLLECTION_FROM_SHIPPING_COMPANY = 5;

    public function label(): string
    {
        return match($this) {
            self::CASH => 'دفع عند الاستلام',
            self::COLLECTION_FROM_SHIPPING_COMPANY => 'تحصيل من شركة الشحن',
        };
    }
}
