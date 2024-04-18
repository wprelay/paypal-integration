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
            do_action('rwp_before_init');
            Route::register();

            static::registerShortCodes();
            do_action('rwp_after_init');
        }, 1);
    }

    public static function registerShortCodes()
    {
        $enable_registration = Settings::get('affiliate_settings.general.allow_affiliate_registration');


        add_shortcode('affiliate_go_registration_form', function () use ($enable_registration) {

            $plugin_name = RWP_PLUGIN_NAME;
            if (!$enable_registration) {
                $notice_html = '<div class="woocommerce-message woocommerce-error">' . esc_html("Affiliate Registration Not Enabled in {$plugin_name}") . '</div>';
                return $notice_html;
            }

            $site_key = Settings::get('affiliate_settings.recaptcha.site_key');
            $secret_key = Settings::get('affiliate_settings.recaptcha.secret_key');

            if (empty($site_key) || empty($secret_key)) {
                $notice_html = '<div class="woocommerce-message woocommerce-error">' . esc_html("Google Recaptcha Keys are not configured in {$plugin_name}") . '</div>';
                return $notice_html;
            }

            $pluginSlug = RWP_PLUGIN_SLUG;
            $registrationScriptHandle = "{$pluginSlug}-registration-script";
            $registrationHandle = "{$pluginSlug}-registration";
            $storeConfig = AssetsActions::getStoreConfigValues();
            $isPro = PluginHelper::isPRO();

            $resourcePath = PluginHelper::getResourceURL();
            wp_enqueue_script($registrationScriptHandle, "{$resourcePath}/scripts/registration.js", array('jquery'), RWP_VERSION, true);
            wp_enqueue_style($registrationHandle, "{$resourcePath}/css/registration.css", [], RWP_VERSION);
            wp_localize_script($registrationScriptHandle, 'wp_relay_store', $storeConfig);
            $site_key = Settings::get('affiliate_settings.recaptcha.site_key');

            if (!empty($site_key)) {
                wp_enqueue_script('google-recaptcha', "https://www.google.com/recaptcha/api.js?render={$site_key}", array(), RWP_VERSION, true);
            }

            $email = '';
            $firstName = '';
            $lastName = '';

            if (is_user_logged_in()) {
                global $current_user;
                $email = $current_user->user_email;
                $firstName = $current_user->user_firstname;
                $lastName = $current_user->user_lastname;
            }

            if (function_exists('WC')) {
                $countries = WC()->countries->get_countries();
            } else {
                $countries = [];
            }

            $nonce = [
                '_wp_nonce_key' => 'affiliate_registration_nonce',
                '_wp_nonce' => WordpressHelper::createNonce('affiliate_registration_nonce')
            ];

            $actionName = is_user_logged_in() ? 'wp_relay' : 'guest_apis';
            $colors = Settings::get('general_settings.color_settings');

            if (!is_array($countries) && empty($values)) {
                return [];
            }

            $path = RWP_PLUGIN_PATH . 'resources/pages/';

            ob_start(); // Start output buffering
            include $path . '/registration.php'; // Include the PHP file
            $content = ob_get_clean();

            return $content;
        });


        add_shortcode('confirmation_email', function () {
            $email = '';
            $firstName = '';
            $lastName = '';

            if (is_user_logged_in()) {
                global $current_user;
                $email = $current_user->user_email;
                $firstName = $current_user->user_firstname;
                $lastName = $current_user->user_lastname;
            }

            $countries = WC()->countries->get_countries();

            if (!is_array($countries) && empty($values)) {
                return [];
            }

            $path = RWP_PLUGIN_PATH . 'resources/pages/';

            ob_start(); // Start output buffering
            include $path . '/registration.php'; // Include the PHP file
            $content = ob_get_clean();

            return $content;

        });
    }
}