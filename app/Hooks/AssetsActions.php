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
        add_action('wp_enqueue_scripts', [__CLASS__, 'addStoreFrontScripts']);
    }

    public static function addAdminPluginAssets($hook)
    {
        if (strpos($hook, 'wp-relay') !== false) {
            $reactDistUrl = PluginHelper::getReactAssetURL();
            $resourceUrl = PluginHelper::getResourceURL();

            wp_enqueue_style('wp-relay-plugin-styles', "{$reactDistUrl}/main.css", [], RWP_VERSION);
            wp_enqueue_script('wp-relay-plugin-script', "{$reactDistUrl}/main.bundle.js", array('wp-element'), RWP_VERSION, true);
            wp_enqueue_style('wp-relay-plugin-styles-font-awesome', "{$resourceUrl}/admin/css/rwp-fonts.css", [], RWP_VERSION);
            remove_all_actions('admin_notices');
        }
    }

    public static function addStoreFrontScripts()
    {
        $pluginSlug = RWP_PLUGIN_SLUG;
        $handle = "{$pluginSlug}-track-order-script";

        if (is_front_page() || is_checkout()) {
            $resourceUrl = PluginHelper::getResourceURL();
            $storeConfig = AssetsActions::getStoreConfigValues();

            wp_enqueue_script($handle, "{$resourceUrl}/scripts/track_wprelay_order.js", array('jquery'), RWP_VERSION, true);
            wp_localize_script($handle, 'wp_relay_store', $storeConfig);
        }
    }

    public static function getStoreConfigValues()
    {
        return [
            'home_url' => get_home_url(),
            'admin_url' => admin_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'recaptcha_site_key' => Settings::get('affiliate_settings.recaptcha.site_key'),
            'affiliate_url_variable' => Settings::get('affiliate_settings.url_options.url_variable'),
            'cookie_duration' => Settings::get('general_settings.cookie_duration'),
            'nonces' => [
                'wprelay_state_list_nonce' => WordpressHelper::createNonce('wprelay_state_list_nonce'),
            ]
        ];
    }
}