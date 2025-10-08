<?php

namespace App;

enum SafeStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string
    {
        return match ($this) {
            SafeStatus::ACTIVE => 'نشط',
            SafeStatus::INACTIVE => 'غير نشط',
        };
    }

    public function englishLabel(): string
    {
        return match ($this) {
            SafeStatus::ACTIVE => 'Active',
            SafeStatus::INACTIVE => 'Inactive',
        };
    }

    public function style(): string
    {
        return match ($this) {
            SafeStatus::ACTIVE => 'success',
            SafeStatus::INACTIVE => 'danger',
        };
    }

    public function color(): string
    {
        return match ($this) {
            SafeStatus::ACTIVE => 'green',
            SafeStatus::INACTIVE => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            SafeStatus::ACTIVE => 'check-circle',
            SafeStatus::INACTIVE => 'x-circle',
        };
    }

    public static function fromBoolean(bool $status): SafeStatus
    {
        return $status ? SafeStatus::ACTIVE : SafeStatus::INACTIVE;
    }

    public function toBoolean(): bool
    {
        return $this === SafeStatus::ACTIVE;
    }
}
