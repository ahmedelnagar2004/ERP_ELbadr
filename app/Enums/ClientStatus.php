<?php

namespace App\Enums;

enum ClientStatus: int
{
    case LOCAL = 0;
    case WEBSITE = 1;

    public function label(): string
    {
        return match ($this) {
            self::WEBSITE => 'موقع إلكتروني',
            self::LOCAL => 'محلي',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WEBSITE => 'bg-green-500 text-white',
            self::LOCAL => 'bg-red-500 text-white',
        };
    }
}
