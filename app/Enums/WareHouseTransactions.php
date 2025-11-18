<?php

namespace App\Enums;

enum WareHouseTransactions: int
{
    case Add = 1;
    case Remove = 2;
    case Init = 3;
    case Adjust = 4;

    public function label(): string
    {
        return match ($this) {
            self::Add => 'Add',
            self::Remove => 'Remove',
            self::Init => 'Init',
            self::Adjust => 'Adjust',
        };
    }

    public function value(): int
    {
        return $this->value;
    }
}
