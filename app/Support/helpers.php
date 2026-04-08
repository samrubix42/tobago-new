<?php

use App\Models\Setting;

if (! function_exists('app_setting')) {
    function app_setting(string $key, mixed $default = null): mixed
    {
        static $cache = null;

        if ($cache === null) {
            $cache = Setting::query()->pluck('value', 'key')->toArray();
        }

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        if ($default !== null) {
            return $default;
        }

        $defaults = app_setting_defaults();

        return $defaults[$key] ?? null;
    }
}

if (! function_exists('app_setting_defaults')) {
    function app_setting_defaults(): array
    {
        return [
            'delivery_fee' => '0',
            'free_delivery_amount' => '0',
            'logo' => '',
            'project_name' => 'Tobac-Go',
            'phone_number' => '',
            'email' => '',
            'whatsapp_number' => '',
            'address' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'facebook_url' => '',
            'x_url' => '',
            'application_name' => config('app.name', 'Laravel'),
            'footer_text' => '',
        ];
    }
}
