<?php

namespace RelayWP\Paypal\App\Hooks;

defined('ABSPATH') or exit;


class CustomHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerHooks('custom-hooks.php');
    }
}

