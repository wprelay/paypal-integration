<?php

namespace RelayWp\Paypal;

use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    public static function environment()
    {
        $clientId = 'AYOLJPWjjChr_qiq9QphYdaf94GzuwWavS3-VXJOTik_C3GTnRyjvdGSOv6jFun0v01pOISfzSxahYvT';
        $clientSecret = 'EObLL2pP6JiQ_ewVEwY4mkPdNEC6j_JoKu3Cxgry4QeXxmzxV-k7leptT9a_KtIQxa95kmr48WrqKUmJ';
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}
