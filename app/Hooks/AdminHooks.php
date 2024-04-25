<?php

namespace WPRelay\Paypal\App\Hooks;

use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\Src\Controllers\Admin\PageController;

class AdminHooks extends RegisterHooks
{
    public static function register()
    {
        static::registerHooks('admin-hooks.php');
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
            esc_html__(WPR_PAYPAL_PLUGIN_NAME, 'wprelay'),
            esc_html__(WPR_PAYPAL_PLUGIN_NAME, 'wprelay'),
            'manage_options',
            'wprelay-paypal',
            [PageController::class, 'show'],
            'dashicons-money',
            56
        );
    }

}