<?php


/**
 * Plugin Name:          Paypal - WP Relay
 * Description:          Payment GateWay for WP-Relay
 * Version:              0.0.1
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
defined('WPR_PAYPAL_VERSION') or define('WPR_PAYPAL_VERSION', "0.0.1");
defined('WPR_PAYPAL_PREFIX') or define('WPR_PAYPAL_PREFIX', "prefix_");

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

if (class_exists('WPRelay\Paypal\App\App')) {
    //If the Directory Exists it means it's a pro pack;
    //Check Whether it is PRO USER

    $app = \WPRelay\Paypal\App\App::make();

    $app->bootstrap(); // to load the plugin
} else {
//    wp_die('Plugin is unable to find the App class.');
    return;
}

add_action('admin_head', function () {
    $page = !empty($_GET['page']) ? $_GET['page'] : '';
    if (in_array($page, array('wp-relay'))) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                self = window;
            });
        </script>
        <?php
    }
}, 11);

