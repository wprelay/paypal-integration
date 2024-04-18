<?php

namespace WPRelay\Paypal\App\Hooks;


class CustomHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerCoreHooks('custom-hooks.php');

        if (rwp_app()->get('is_pro_plugin')) {
            static::registerProHooks('custom-hooks.php');
        }
    }
}