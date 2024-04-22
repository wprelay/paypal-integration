<?php

namespace WPRelay\Paypal\Src;

use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;

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
        //read from DB.
        $clientId = 'AYOLJPWjjChr_qiq9QphYdaf94GzuwWavS3-VXJOTik_C3GTnRyjvdGSOv6jFun0v01pOISfzSxahYvT';
        $clientSecret = 'EObLL2pP6JiQ_ewVEwY4mkPdNEC6j_JoKu3Cxgry4QeXxmzxV-k7leptT9a_KtIQxa95kmr48WrqKUmJ';
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    public function processPayout($body)
    {
        $payoutRequest = new PayoutsPostRequest();

        $payoutRequest->body = $body;

        $client = static::client();

        $client->authInjector->inject($payoutRequest);

        $response = $client->execute($payoutRequest);
    }

    public static function preparePayoutData()
    {
        $data =
            [
                "items" => [
                    [
                        "receiver" => "sb-vru3g30472026@personal.example.com",
                        "amount" => [
                            "currency" => "USD",
                            "value" => "10"
                        ],
                        "recipient_type" => "EMAIL",
                        "note" => "Thanks for your patronage!",
                        "sender_item_id" => "201403140001",
                        "recipient_wallet" => "PAYPAL"
                    ]
                ],
                ["sender_batch_header" => [
                    "sender_batch_id" => "Payouts_2020_100007",
                    "email_subject" => "You have a payout!",
                    "email_message" => "You have received a payout! Thanks for using our service!"
                ]
                ]
            ];
    }
}
