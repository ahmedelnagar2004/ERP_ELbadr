<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('company.name', 'Elbadr ERP');
        $this->migrator->add('company.email', 'admin@elbadr.com');
        $this->migrator->add('company.phone', '0123456789');
        $this->migrator->add('company.address', 'Cairo, Egypt');
        $this->migrator->add('company.logo', null);
    }
};
