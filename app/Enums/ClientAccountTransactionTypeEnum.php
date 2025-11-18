<?php

namespace App\Enums;

enum ClientAccountTransactionTypeEnum: int
{
    case CREDIT = 1;  // دائن (Inflow) - العميل يدفع
    case DEBIT = 2;   // مدين (Outflow) - العميل يشتري بالآجل

    public function label(): string
    {
        return match ($this) {
            self::CREDIT => 'دائن (تحصيل)',
            self::DEBIT => 'مدين (آجل)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CREDIT => 'bg-green-500 text-white',
            self::DEBIT => 'bg-red-500 text-white',
        };
    }
}
