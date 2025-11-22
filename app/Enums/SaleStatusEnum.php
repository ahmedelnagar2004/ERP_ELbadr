<?php

namespace App\Enums;

enum SaleStatusEnum: int
{
    case SALE = 0;
    case RETURN = 1;


    public function label(): string
    {
        return match ($this) {
            self::SALE => 'sale',
            self::RETURN => 'return',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SALE => 'bg-green-500 text-white',
            self::RETURN => 'bg-red-500 text-white',
        };
    }
}
