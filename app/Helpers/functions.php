<?php

use App\Settings\CompanySettings;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        $settings = app(CompanySettings::class);
        
        // Map the key to match the CompanySettings property if needed
        $key = $key === 'site_name' ? 'name' : $key;
        $key = $key === 'site_email' ? 'email' : $key;
        $key = $key === 'site_phone' ? 'phone' : $key;
        $key = $key === 'site_address' ? 'address' : $key;
        $key = $key === 'site_logo' ? 'logo' : $key;
        
        return $settings->$key ?? $default;
    }
}
