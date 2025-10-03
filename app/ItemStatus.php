<?php

namespace App;

enum ItemStatus
{
    case Shown;
    case Hidden;

    public function label(): string
    {
        return match ($this) {
            self::Shown => 'معروض في المتجر',
            self::Hidden => 'مخفي من المتجر',
        };
    }

    public function value(): int
    {
        return match ($this) {
            self::Shown => 1,
            self::Hidden => 0,
        };
    }

    public function style(): string
    {
        return match ($this) {
            self::Shown => 'bg-green-500 text-white',
            self::Hidden => 'bg-red-500 text-white',
        };
    }
}
