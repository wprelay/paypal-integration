<?php

namespace WPRelay\Paypal\App\Helpers;

use Exception;

defined('ABSPATH') or exit;

class PluginHelper
{
    public static function pluginRoutePath($pro = false)
    {

        return WPR_PAYPAL_PLUGIN_PATH . 'src/routes';
    }

    public static function getResourceURL()
    {

        return WPR_PAYPAL_PLUGIN_URL . 'resources';
    }

    public static function getReactAssetURL()
    {
        return WPR_PAYPAL_PLUGIN_URL . 'paypal-ui/dist';
    }

    public static function logError($message, $location = [], $exception = null)
    {
        if (empty($location)) {
            $log_message = $message;
        } else {
            $log_message = "Error At: {$location[0]}@{$location[1]} => `{$message}` ";
        }
        // Create a log message

        // If an exception object is provided, append its details to the log message
        if (($exception instanceof Exception) || ($exception instanceof \Error)) {
            $log_message .= "\nTrace Details: " . $exception->getTraceAsString();
            $log_message .= "\nActual Message: " . $exception->getMessage();
        }

        // Log the error message to the WordPress error log
        error_log($log_message);
    }

    public static function getAdminDashboard()
    {
        $name = WPR_PAYPAL_MAIN_PAGE;
        return admin_url("admin.php?page={$name}#/");
    }

    public static function getPayoutIdWithUniqueId($payout_id)
    {
        $unique_id = Functions::getUniqueId();
        return "pid-{$payout_id}-$unique_id";
    }

}