<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    public string $name;
    public string $email;
    public string $phone;
    public string $address;
    public ?string $logo;

    public static function group(): string
    {
        return 'company';
    }
}


