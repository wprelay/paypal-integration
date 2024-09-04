<?php

namespace RelayWP\Paypal\App\Hooks;

class WPHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerHooks('wp-hooks.php');
    }
}