<?php

namespace WPRelay\Paypal\Src\Controllers\Admin;

use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\App\Services\Request\Request;
use WPRelay\Paypal\App\Services\Request\Response;

class SettingsController
{
    public static function getSettings(Request $request)
    {
        try {
            $data = get_option('wpr_paypal_settings', '{}');
            $settings = json_decode($data, true);
            $settings = $settings['paypal_settings'] ?? [];

            $sandbox_mode = $settings['sandbox_mode'] ?? '';
            $client_secret = $settings['client_secret'] ?? '';
            $client_id = $settings['client_id'] ?? '';
            $payment_via = $settings['payment_via'] ?? '';

            $username= $settings['user_name'] ?? '';
            $password= $settings['password'] ?? '';
            $signature= $settings['signature'] ?? '';


            $webhook_url = static::getWebhookController();
            $ipn_notification_url = static::ipnNotificationUrl();
            Response::success([
                'sandbox_mode' => $sandbox_mode,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'webhook_url' => $webhook_url,
                'ipn_notification_url' => $ipn_notification_url,
                'payment_via' => $payment_via,
                'username'  => $username,
                'password'  => $password,
                'signature'  => $signature,
            ]);
        } catch (\Exception|\Error $exception) {
            PluginHelper::logError('Error Occurred While Processing', [__CLASS__, __FUNCTION__], $exception);
            return Response::error();
        }
    }

    public static function saveSettings(Request $request)
    {
        try {
            $sandbox_mode = $request->get('sandbox_mode');
            $client_id = $request->get('client_id');
            $client_secret = $request->get('client_secret');
            $username = $request->get('username');
            $password = $request->get('password');
            $signature = $request->get('signature');
            $payment_via = $request->get('payment_via');

            $settings = [
                'sandbox_mode' => (bool)$sandbox_mode,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'payment_via' => $payment_via,
                'user_name' => $username,
                'password' => $password,
                'signature' => $signature
            ];

            $settings = json_encode(['paypal_settings' => $settings]);

            update_option('wpr_paypal_settings', $settings);

        } catch (\Exception|\Error $exception) {
            PluginHelper::logError('Error Occurred While Processing', [__CLASS__, __FUNCTION__], $exception);
            return Response::error();
        }
    }

    public static function getWebhookController()
    {
        $home_url = home_url();
       return  $home_url. "/wp-json/webhook/v1/paypal";
    }

    public static function ipnNotificationUrl()
    {
        $home_url = home_url();
        return  $home_url. "/wp-json/ipn/notifications/paypal";
    }
}