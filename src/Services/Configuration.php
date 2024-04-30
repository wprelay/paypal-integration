<?php

namespace WPRelay\Paypal\Src\Services;

class Configuration
{

    public static function getConfig()
    {
        $config = array(
            // values: 'sandbox' for testing
            //		   'live' for production
            //         'tls' for testing if your server supports TLSv1.2
            "mode" => "sandbox",
            // TLSv1.2 Check: Comment the above line, and switch the mode to tls as shown below
            // "mode" => "tls"

            'log.LogEnabled' => true,
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
        $config = array(
            // Signature Credential
            "acct1.UserName" => "sb-4aqlq29883972_api1.business.example.com",
            "acct1.Password" => "BF22P95N7W6AM8S3",
            "acct1.Signature" => "AF9VR7ZXR4iSsKgl1AQlasp-euxyA2K1dz81sCFIaZPVfOxDx-lX1UpV",
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