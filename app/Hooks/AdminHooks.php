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
        add_submenu_page(
            null,
            esc_html__(WPR_PAYPAL_PLUGIN_NAME, 'wprelay-paypal'),
            esc_html__(WPR_PAYPAL_PLUGIN_NAME, 'wprelay-paypal'),
            'manage_options',
            WPR_PAYPAL_MAIN_PAGE,
            [PageController::class, 'show'],
            100
        );
    }
}