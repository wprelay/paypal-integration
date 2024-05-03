<?php

namespace WPRelay\Paypal\App\Services;

use WPRelay\Paypal\App\Helpers\Functions;

class Settings
{
    private static $settings = [];

    public static function get($key, $default = null)
    {
        if (empty(static::$settings)) {
            static::$settings = static::fetchSettings();
        }

        return Functions::dataGet(static::$settings, $key, $default);
    }

    public static function fetchSettings()
    {
        $wpr_settings = get_option('wpr_paypal_settings', '[]');

        return json_decode($wpr_settings, true);

    }
}
