<?php

namespace App;

enum UnitStatus: int
{
    case Active = 1;
    case Inactive = 0;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function value(): int
    {
        return $this->value;
    }

    public function style(): string
    {
        return match ($this) {
            self::Active => 'bg-green-500 text-white',
            self::Inactive => 'bg-red-500 text-white',
        };
    }

    public static function fromString(string $status): self
    {
        return match (strtolower($status)) {
            'active', '1' => self::Active,
            'inactive', '0' => self::Inactive,
            default => throw new \InvalidArgumentException("Invalid status value: $status"),
        };
    }
}

