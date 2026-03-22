<?php

/**
 * Dynamic Platform Configuration — Static Defaults Only.
 *
 * ⚠️  DO NOT query the database here.
 *     Config files are loaded BEFORE service providers boot (before 'view',
 *     'db', etc. are registered). Any DB call here will cause a fatal
 *     "Class view does not exist" error on boot.
 *
 * Runtime values from the `settings` table are merged in
 * AppServiceProvider::boot() AFTER the container is fully initialized.
 *
 * Access: config('custom.key') or Setting::get('key', 'default')
 */

return [
    'company_name'    => env('COMPANY_NAME', 'TC Service Center'),
    'company_logo'    => null,
    'company_favicon' => null,
    'company_gst'     => '',
    'company_address' => '',
    'support_phone'   => env('SUPPORT_PHONE', ''),
    'support_email'   => env('SUPPORT_EMAIL', ''),
    'theme_color'     => env('THEME_COLOR', '#4f46e5'),
    'dark_mode'       => 'enabled',
    'homepage_banner' => null,
];
