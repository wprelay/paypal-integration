<?php

namespace WPRelay\Paypal\Src;

use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\ProductionEnvironment;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use WPRelay\Paypal\App\Helpers\Functions;
use WPRelay\Paypal\App\Services\Settings;
use WPRelay\Paypal\Src\Models\BatchPayout;
use WPRelay\Paypal\Src\Models\BatchPayoutItem;

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
        $paypalSettings = Settings::get('paypal_settings');

        $client_id = $paypalSettings['client_id'];
        $sandbox_mode = $paypalSettings['sandbox_mode'];
        $client_secret = $paypalSettings['client_secret'];
        //read from DB.
        $clientId = $client_id;
        $clientSecret = $client_secret;


        if ($sandbox_mode) {
            return new SandboxEnvironment($clientId, $clientSecret);
        }

        return new ProductionEnvironment($clientId, $clientSecret);

    }

    public static function processPayout($data)
    {
        $body = static::prepareAndCreatePayoutData($data);

        $payoutRequest = new PayoutsPostRequest();

        $payoutRequest->body = $body;

        $client = static::client();

        $client->authInjector->inject($payoutRequest);

        $response = $client->execute($payoutRequest);

        $statusCode = $response->statusCode;
        if ($statusCode >= 200 && $statusCode < 300) {
            $result = $response->result;
            return true;
        } else {
            return false;
        }
    }

    public static function prepareAndCreatePayoutData($affiliate_data)
    {
        $items = [];

        $batch_id = substr(md5(time()), 0, 6);
        $year = date('Y');

        $data =
            [
                "sender_batch_header" => $sender_batch_details = [
                    "sender_batch_id" => "Payouts_{$year}_{$batch_id}",
                    "email_subject" => "You have a payout!",
                    "email_message" => "You have received a payout! Thanks for using our service!"
                ]
            ];

        BatchPayout::query()->create([
            'sender_batch_id' => $sender_batch_details['sender_batch_id'],
            'payout_batch_id' => null,
            'batch_status' => 'PENDING',
            'email_message' => $sender_batch_details['email_message'],
            'email_subject' => $sender_batch_details['email_subject'],
        ]);

        $last_batch_id = BatchPayout::query()->lastInsertedId();
        foreach ($affiliate_data as $pending_payment) {
            $item_id = Functions::getUniqueId();
            $items[] = $item = [
                "receiver" => $pending_payment['affiliate_email'],
                "amount" => [
                    "currency" => $pending_payment['currency'],
                    "value" => $pending_payment['commission_amount']
                ],
                "recipient_type" => "EMAIL",
                "note" => "Thanks for your patronage!",
                "sender_item_id" => $item_id,
                "recipient_wallet" => "PAYPAL"
            ];

            BatchPayoutItem::create([
                'batch_id' => $last_batch_id,
                'receiver_email' => $item['receiver'],
                'currency_code' => $item['amount']['currency'],
                'amount' => $item['amount']['value'],
                'transaction_status' => 'PENDING',
                'sender_item_id' => $item['sender_item_id'],
                'affiliate_id' => $pending_payment['affiliate_id'],
                'affiliate_payout_id' => $pending_payment['affiliate_payout_id'],
            ]);
        }

        $data['items'] = $items;

        return $data;
    }

    public static function getSupportedCurrencies()
    {
        return [
            'AUD' => 'Australian dollar',
            'BRL' => 'Brazilian real',
            'CAD' => 'Canandian dollar',
            'CNY' => 'Chinese Renmenbi',
            'CZK' => 'Czech koruna',
            'DKK' => 'Danish Krone',
            'EUR' => 'Euro',
            'HKD' => 'Hong Kong dollar',
            'HUF' => 'Hungarian Forint',
            'ILS' => 'Israeli new shekel',
            'JPY' => 'Japanese Yen',
            'MYR' => 'Malaysian ringgit',
            'MXN' => 'Mexican peso',
            'TWD' => 'New Taiwan dollar',
            'NZD' => 'New Zealand dollar',
            'NOK' => 'Norwegian krone',
            'PHP' => 'Philippine peso',
            'PLN' => 'Polish zloty',
            'GBP' => 'Pound sterling',
            'SGD' => 'Singapore dollar',
            'SEK' => 'Swedish krona',
            'CHF' => 'Swiss franc',
            'THB' => 'Thai baht',
            'USD' => 'United States dollar'
        ];
    }

    public static function isCurrencyAvailableForPayment($code)
    {
        $currencies = static::getSupportedCurrencies();

        return in_array($code, array_keys($currencies));
    }
}
