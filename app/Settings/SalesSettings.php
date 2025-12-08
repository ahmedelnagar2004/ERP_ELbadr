<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SalesSettings extends Settings
{
    public bool $allow_decimal_quantities;
    public string $default_discount_type;
    public array $enabled_payment_methods;
    public bool $allow_negative_stock;

    public static function group(): string
    {
        return 'sales';
    }
}
