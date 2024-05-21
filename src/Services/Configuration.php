<?php

namespace WPRelay\Paypal\Src\Services;

use WPRelay\Paypal\App\Services\Settings;

class Configuration
{

    public static function getConfig()
    {
        $config = array(
            'log.LogEnabled' => false,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'FINE'

            // These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
            // "http.ConnectionTimeOut" => "5000",
            // "http.Retry" => "2",
        );
        return $config;
    }

    // Creates a configuration array containing credentials and other required configuration parameters.
    public static function getAcctAndConfig()
    {
        $paypalSettings = Settings::get('paypal_settings');

        $mode = 'sandbox';

        if (isset($paypalSettings['sandbox_mode'])) {
            $mode = $paypalSettings['sandbox_mode'] ? 'sandbox' : 'production';
        }

        $userName = $paypalSettings['user_name'] ?? '';
        $password = $paypalSettings['password'] ?? '';
        $signature = $paypalSettings['signature'] ?? '';

        $config = array(
            // Signature Credential
            "acct1.UserName" => $userName,
            "acct1.Password" => $password,
            "acct1.Signature" => $signature,
            // values: 'sandbox' for testing
            //		   'live' for production
            //         'tls' for testing if your server supports TLSv1.2
            "mode" => $mode,
            // TLSv1.2 Check: Comment the above line, and switch the mode to tls as shown below
            // "mode" => "tls"

            // Subject is optional and is required only in case of third party authorization
            // "acct1.Subject" => "",

            // Sample Certificate Credential
            // "acct1.UserName" => "certuser_biz_api1.paypal.com",
            // "acct1.Password" => "D6JNKKULHN3G5B8A",
            // Certificate path relative to config folder or absolute path in file system
            // "acct1.CertPath" => "cert_key.pem",
            // Subject is optional and is required only in case of third party authorization
            // "acct1.Subject" => "",
        );

        return array_merge($config, self::getConfig());
    }
}