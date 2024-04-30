<?php


/**
 * Plugin Name:          Paypal - WP Relay
 * Description:          Payment GateWay for WP-Relay
 * Version:              0.0.4
 * Requires at least:    5.9
 * Requires PHP:         7.3
 * Author:               WPRelay * Author URI:           https://www.wprelay.com
 * Text Domain:          flycart.org
 * Domain Path:          /i18n/languages
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 *
 * WC requires at least: 7.0
 * WC tested up to:      8.1
 */

defined('ABSPATH') or exit;

defined('WPR_PAYPAL_PLUGIN_PATH') or define('WPR_PAYPAL_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('WPR_PAYPAL_PLUGIN_URL') or define('WPR_PAYPAL_PLUGIN_URL', plugin_dir_url(__FILE__));
defined('WPR_PAYPAL_PLUGIN_FILE') or define('WPR_PAYPAL_PLUGIN_FILE', __FILE__);
defined('WPR_PAYPAL_PLUGIN_NAME') or define('WPR_PAYPAL_PLUGIN_NAME', "WPRelay-Paypal");
defined('WPR_PAYPAL_PLUGIN_SLUG') or define('WPR_PAYPAL_PLUGIN_SLUG', "WPRelay-Paypal");
defined('WPR_PAYPAL_VERSION') or define('WPR_PAYPAL_VERSION', "0.0.4");
defined('WPR_PAYPAL_PREFIX') or define('WPR_PAYPAL_PREFIX', "prefix_");
defined('WPR_PAYPAL_MAIN_PAGE') or define('WPR_PAYPAL_MAIN_PAGE', "wprelay-paypal");

defined('WPR_PAYPAL_SANDBOX_URL') or define('WPR_PAYPAL_SANDBOX_URL', "https://api-m.sandbox.paypal.com");
defined('WPR_PAYPAL_LIVE_URL') or define('WPR_PAYPAL_LIVE_URL', "https://api-m.paypal.com");

/**
 * Required PHP Version
 */
if (!defined('WPR_PAYPAL_REQUIRED_PHP_VERSION')) {
    define('WPR_PAYPAL_REQUIRED_PHP_VERSION', 7.2);
}

$php_version = phpversion();


if (version_compare($php_version, WPR_PAYPAL_REQUIRED_PHP_VERSION) > 1) {
    error_log("Minimum PHP Version Required Is " . WPR_PAYPAL_REQUIRED_PHP_VERSION);
    return;
}

if (file_exists(WPR_PAYPAL_PLUGIN_PATH . '/vendor/autoload.php')) {
    require WPR_PAYPAL_PLUGIN_PATH . '/vendor/autoload.php';
} else {
    error_log('Vendor directory is not found');
    return;
}

if (file_exists(WPR_PAYPAL_PLUGIN_PATH . '/packages/merchant-sdk-php/vendor/autoload.php')) {
    require WPR_PAYPAL_PLUGIN_PATH . '/packages/merchant-sdk-php/vendor/autoload.php';
} else {
    error_log('Merchant SDK PHP Vendor directory is not found');
    return;
}

if (file_exists(WPR_PAYPAL_PLUGIN_PATH . '/packages/paypal-sdk-core-php-main/vendor/autoload.php')) {
    require WPR_PAYPAL_PLUGIN_PATH . '/packages/paypal-sdk-core-php-main/vendor/autoload.php';
} else {
    error_log('PAYPAL SDK CORE PHP MAIN - Vendor directory is not found');
    return;
}

if (!function_exists('wpr_check_is_wp_relay_pro_installed')) {
    function wpr_check_is_wp_relay_pro_installed()
    {
        $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'wprelay-pro/wprelay-pro.php';
        return in_array($plugin_path, wp_get_active_and_valid_plugins());
    }
}

if (function_exists('wpr_check_is_wp_relay_pro_installed')) {
    if(!wpr_check_is_wp_relay_pro_installed()) {
        add_action('admin_notices', 'add_wprelay_not_installed_notice');
        error_log('Unable to Processed.  WPRelay Plugin is Not activated');
        return;
    }
}


//Loading woo-commerce action schedular
require_once(plugin_dir_path(__FILE__) . '../woocommerce/packages/action-scheduler/action-scheduler.php');

if (class_exists('WPRelay\Paypal\App\App')) {
    //If the Directory Exists it means it's a pro pack;
    //Check Whether it is PRO USER

    $app = \WPRelay\Paypal\App\App::make();

    $app->bootstrap(); // to load the plugin
} else {
//    wp_die('Plugin is unable to find the App class.');
    return;
}

/**
 * To set plugin is compatible for WC Custom Order Table (HPOS) feature.
 */
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

add_action('admin_head', function () {
    $page = !empty($_GET['page']) ? $_GET['page'] : '';
    $main_page_name = WPR_PAYPAL_MAIN_PAGE;
    if (in_array($page, array($main_page_name))) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                self = window;
            });
        </script>
        <?php
    }
}, 11);

function add_wprelay_not_installed_notice() {
    $class = 'notice notice-warning';
    $name = WPR_PAYPAL_PLUGIN_NAME;
    $message = __( "Error you did not installed the WPRelay Plugin to work with {$name}", 'text-domain' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}


add_action('init', function() {
    error_log('action registered');
  if(isset($_GET['mass_pay'])) {
      error_log('mass pay query param is set');
     $massPayment = new \WPRelay\Paypal\Src\Services\MassPay() ;

     error_log('mass payment object created');

     return $massPayment->pay();

  }
});


/**
 *Paypal Packages
 * https://github.com/smashgg/paypal-sdk-core-php
 */