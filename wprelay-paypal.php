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

use RelayWp\Paypal\Paypal;

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

function render_paypal_settings_page()
{
    $file = WPR_PAYPAL_PLUGIN_PATH . 'admin.php';
    $data = get_option('wp_relay_paypal_settings', "{}");


    if (file_exists($file)) {
        $data = json_decode($data, true);
        ob_start();
        extract($data);
        include $file;
        echo ob_get_clean();
    } else {
        error_log('file not exists');
    }
    return false;
}

add_action('wp_ajax_save_paypal_details', function () {
    try {

        $nonce = isset($_POST['_wp_relay_paypal_nonce']) ? sanitize_text_field($_POST['_wp_relay_paypal_nonce']) : '';
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($nonce) && wp_verify_nonce($nonce, '_wprelay_papal_nonce')) {
            $client_id = $_POST['client_id'];
            $client_secret = $_POST['client_secret'];
            $payment_type = $_POST['payment_type'];
            $account_type = $_POST['account_type'];

            $errors = [];

            if (empty($client_id)) {
                $errors['client_id'] = ['Client Id is Required'];
            }

            if (empty($client_secret)) {
                $errors['client_secret'] = ['Client Secret is Required'];
            }

            if (empty($payment_type)) {
                $errors['payment_type'] = ['Payment Type is Required'];
            }

            if (empty($account_type)) {
                $errors['account_type'] = ['Account Type is Required'];
            }

            if (!empty($errors)) {
                wp_send_json_error($errors, 422);
            }

            $data['client_id'] = $client_id;
            $data['client_secret'] = $client_secret;
            $data['payment_type'] = $payment_type;

            $data = json_encode($data);

            update_option('wp_relay_paypal_settings', $data);

            wp_send_json_success([
                'message' => 'WPRelay Paypal Settings Saved Successfully'
            ]);

        } else {
            wp_send_json_error(['message' => 'Invalid Request', 401]);
        }
    } catch (Error $error) {
        wp_send_json_error(['message' => 'Server Error Occurred'], 500);
    }
});

//add_filter('rwp_payment_process_sources', [Paypal::class, 'addPaypalPayment'], 10, 2);
