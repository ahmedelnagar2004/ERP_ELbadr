<?php

namespace App;

enum UnitStatus
{
    case Active;
    case Inactive;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
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

    /**
     * تحويل النص إلى enum
     */
    public static function fromString(string $status): self
    {
        return match (strtolower($status)) {
            'active', '1' => self::Active,
            'inactive', '0' => self::Inactive,
            default => throw new \InvalidArgumentException("Invalid status value: $status"),
        };
    }
}
