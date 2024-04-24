<?php

namespace WPRelay\Paypal\Src;

use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
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
        //read from DB.
        $clientId = 'Af2oeVTK6DcrtRnV94RkaZZc0p7PGn_Z1URWF-v9_vUH50PiXPQ9nQiMjN8DXWg7jFnw2hpkXU1K-6_r';
        $clientSecret = 'ELhVXNEahnDrwr28T7pDMn9yUNPjBmPlFep61gWPWz3QT87y5bh9ukfe_MNaJiQLB2_f7VlDH2zlm6mS';
        return new SandboxEnvironment($clientId, $clientSecret);
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
            $item_id = substr(md5(uniqid(mt_rand(), true)), 0, 12);
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
}
