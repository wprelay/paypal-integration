<?php

namespace WPRelay\Paypal\App;

use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\App\Helpers\WordpressHelper;
use WPRelay\Paypal\App\Hooks\AssetsActions;
use WPRelay\Paypal\App\Services\Settings;

class App extends Container
{

    public static $app;

    public static function make()
    {
        if (!isset(self::$app)) {
            self::$app = new static();
        }

        return self::$app;
    }

    /* Bootstrap plugin
     */
    public function bootstrap()
    {
        Setup::init();
        add_action('plugins_loaded', function () {
            do_action('wpr_paypal_before_init');
            Route::register();

            do_action('wpr_paypal_after_init');
        }, 1);
    }
}