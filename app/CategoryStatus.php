<?php

namespace App;

enum CategoryStatus: int
{
    case Active = 1;
    case Inactive = 0;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'نشط',
            self::Inactive => 'غير نشط',
        };
    }

    public function style(): string
    {
        return match ($this) {
            self::Active => 'bg-green-500 text-white',
            self::Inactive => 'bg-red-500 text-white',
        };
    }
}


