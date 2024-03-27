<?php


/**
 * Plugin Name:          Paypal - WP Relay
 * Description:          Payment GateWay for WP-Relay
 * Version:              0.0.1
 * Requires at least:    5.9
 * Requires PHP:         7.3
 * Author:               WPRelay
 * Author URI:           https://www.wprelay.com
 * Text Domain:          flycart.org
 * Domain Path:          /i18n/languages
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 *
 * WC requires at least: 7.0
 * WC tested up to:      8.1
 */

use RelayWp\Paypal\Paypal;

defined('ABSPATH') or exit;

defined('RWP_PAYPAL_PLUGIN_PATH') or define('RWP_PAYPAL_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('RWP_PAYPAL_PLUGIN_FILE') or define('RWP_PAYPAL_PLUGIN_FILE', __FILE__);
defined('RWP_PAYPAL_PLUGIN_NAME') or define('RWP_PAYPAL_PLUGIN_NAME', "Paypal");
defined('RWP_PAYPAL_PLUGIN_SLUG') or define('RWP_PAYPAL_PLUGIN_SLUG', "Paypal");
defined('RWP_PAYPAL_VERSION') or define('RWP_PAYPAL_VERSION', "0.0.1");
defined('RWP_PAYPAL_PREFIX') or define('RWP_PAYPAL_PREFIX', "prefix_");

/**
 * Required PHP Version
 */
if (!defined('RWP_PAYPAL_REQUIRED_PHP_VERSION')) {
    define('RWP_PAYPAL_REQUIRED_PHP_VERSION', 7.2);
}

$php_version = phpversion();

if (version_compare($php_version, RWP_PAYPAL_REQUIRED_PHP_VERSION) > 1) {
    error_log("Minimum PHP Version Required Is " . RWP_PAYPAL_REQUIRED_PHP_VERSION);
    return;
}

if (file_exists(RWP_PAYPAL_PLUGIN_PATH . '/vendor/autoload.php')) {
    require RWP_PAYPAL_PLUGIN_PATH . '/vendor/autoload.php';
} else {
    error_log('Vendor directory is not found');
    return;
}

add_filter('rwp_payment_process_sources', [Paypal::class, 'addPaypalPayment'], 10, 2);
