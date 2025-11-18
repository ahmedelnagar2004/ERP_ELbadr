<?php

namespace App\Enums;

enum safeTransactionTypeStatus: int
{
    case in = 1;
    case out = -1;



    public function label(): string
    {
        return match ($this) {
            self::in => 'IN ',
            self::out => 'OUT',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::in => 'bg-green-500 text-white',
            self::out => 'bg-red-500 text-white',
        };
    }
}
