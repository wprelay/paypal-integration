<?php

namespace RelayWP\Paypal\App\Hooks;

defined('ABSPATH') or exit;

class WPHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerHooks('wp-hooks.php');
    }
}

