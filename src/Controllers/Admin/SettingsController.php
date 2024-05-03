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

            $client_secret = $settings['client_secret'] ?? '';
            $client_id = $settings['client_id'] ?? '';
            $payment_via = $settings['payment_via'] ?? '';

            $username= $settings['user_name'] ?? '';
            $password= $settings['password'] ?? '';
            $signature= $settings['signature'] ?? '';


            Response::success([
                'client_id' => $client_id,
                'client_secret' => $client_secret,
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
            $client_id = $request->get('client_id');
            $client_secret = $request->get('client_secret');
            $username = $request->get('username');
            $password = $request->get('password');
            $signature = $request->get('signature');
            $payment_via = $request->get('payment_via');

            $settings = [
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
}