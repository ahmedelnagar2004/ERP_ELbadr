<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sales.allow_decimal_quantities', false);
        $this->migrator->add('sales.default_discount_type', 'fixed');
        $this->migrator->add('sales.enabled_payment_methods', ['cash', 'credit', 'card', 'bank']);
    }
};
