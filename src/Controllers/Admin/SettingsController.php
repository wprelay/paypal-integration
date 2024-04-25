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

            $client_secret = $settings['client_secret'] ?? '';
            $client_id = $settings['client_id'] ?? '';
            $payment_via = $settings['payment_via'] ?? '';

            Response::success([
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'payment_via' => $payment_via,
            ]);
        } catch (\Exception|\Error $exception) {
            PluginHelper::logError('Error Occurred While Processing', [__CLASS__, __FUNCTION__], $exception);
            return Response::error();
        }
    }

    public static function saveSettings(Request $request)
    {

        try {
            $client_id = $request->get('paypal_client_id');
            $client_secret = $request->get('paypal_client_secret');
            $payment_via = $request->get('payment_via');

            $settings = [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'payment_via' => $payment_via,
            ];

            $settings = json_encode($settings);

            update_option('wpr_paypal_settings', $settings);

        } catch (\Exception|\Error $exception) {
            PluginHelper::logError('Error Occurred While Processing', [__CLASS__, __FUNCTION__], $exception);
            return Response::error();
        }
    }
}