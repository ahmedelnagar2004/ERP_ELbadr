<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
         $this->migrator->add('general.site_name', 'albadr system');  
        $this->migrator->add('general.site_logo', 'logo.png');
        $this->migrator->add('general.site_address', '123 Main St');
        $this->migrator->add('general.site_phone', '0123456789');
        $this->migrator->add('general.site_email', 'info@example.com');
    }
};
