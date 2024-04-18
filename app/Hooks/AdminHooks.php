<?php

namespace RelayWp\Affiliate\App\Hooks;

use WPRelay\Paypal\App\Helpers\PluginHelper;

class AdminHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerCoreHooks('admin-hooks.php');
    }

    public static function init()
    {

    }

    public static function head()
    {

    }

    public static function addMenu()
    {
        add_menu_page(
            esc_html__(RWP_PLUGIN_NAME, 'wprelay'),
            esc_html__(RWP_PLUGIN_NAME, 'wprelay'),
            'manage_options',
            'wp-relay',
            [PageController::class, 'show'],
            'dashicons-money',
            56
        );
    }
}