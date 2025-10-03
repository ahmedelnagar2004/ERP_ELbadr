<?php

namespace App;

enum ClientStatus
{
    case Active;
    case Inactive;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'نشط',
            self::Inactive => 'غير نشط',
        };
    }

    public function value(): int
    {
        return match ($this) {
            self::Active => 1,
            self::Inactive => 0,
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
