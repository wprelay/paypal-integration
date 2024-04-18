<?php

namespace WPRelay\Paypal\App\Hooks;

use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\App\Helpers\WordpressHelper;
use WPRelay\Paypal\App\Services\Settings;

defined('ABSPATH') or exit;

class AssetsActions
{
    public static function register()
    {
        static::enqueue();
    }

    /**
     * Enqueue scripts
     */
    public static function enqueue()
    {
        add_action('admin_enqueue_scripts', [__CLASS__, 'addAdminPluginAssets']);
//        add_action('wp_enqueue_scripts', [__CLASS__, 'addStoreFrontScripts']);
    }

    public static function addAdminPluginAssets($hook)
    {
        global $page_now;
        error_log($page_now);
        if (strpos($hook, 'wprelay-paypal') !== false) {

            error_log('enqueued');
            $reactDistUrl = PluginHelper::getReactAssetURL();
            $resourceUrl = PluginHelper::getResourceURL();

            wp_enqueue_style('wp-relay-paypal-plugin-styles', "{$reactDistUrl}/main.css", [], WPR_PAYPAL_VERSION);
            wp_enqueue_script('wp-relay-paypal-plugin-script', "{$reactDistUrl}/main.bundle.js", array('wp-element'), WPR_PAYPAL_VERSION, true);
            wp_enqueue_style('wp-relay-paypal-plugin-styles-font-awesome', "{$resourceUrl}/admin/css/rwp-fonts.css", [], WPR_PAYPAL_VERSION);
            remove_all_actions('admin_notices');
        }
    }
}