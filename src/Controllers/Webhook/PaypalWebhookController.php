<?php

namespace WPRelay\Paypal\Src\Controllers\Webhook;

use WPRelay\Paypal\App\Helpers\Functions;
use WPRelay\Paypal\App\Services\Request\Response;
use WPRelay\Paypal\Src\Models\BatchPayout;
use WPRelay\Paypal\Src\Models\BatchPayoutItem;
use WPRelay\Paypal\Src\Models\MassPayout;
use WPRelay\Paypal\Src\Models\WebhookEvent;
use RelayWp\Affiliate\Core\Models\Payout;
use RelayWp\Affiliate\Core\Models\Transaction;

class PaypalWebhookController
{
    public static function registerRoutes()
    {
        register_rest_route(
            'webhook/v1',
            '/paypal',
            [
                [
                    'methods' => 'POST',
                    'callback' => [__CLASS__, 'handleWebhook'],
                    'permission_callback' => '__return_true'
                ],
            ]
        );

        register_rest_route(
            'ipn/notifications',
            '/paypal',
            [
                [
                    'methods' => 'POST',
                    'callback' => [__CLASS__, 'handleIPNNotifications'],
                    'permission_callback' => '__return_true'
                ],
            ]
        );

    }

    public static function handleWebhook()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        static::createWebhookEntry($data);

        $event_type = $data['event_type'];

        switch ($event_type) {
            case 'PAYMENT.PAYOUTSBATCH.SUCCESS':
                static::batchSucceeded($data['resource']);
                break;
            case 'PAYMENT.PAYOUTSBATCH.DENIED':
                static::batchDenied($data['resource']);
                break;

            case 'PAYMENT.PAYOUTS-ITEM.SUCCEEDED':
                static::payoutItemSucceeded($data['resource']);
                break;

            case 'PAYMENT.PAYOUTS-ITEM.CANCELED':
            case 'PAYMENT.PAYOUTS-ITEM.DENIED':
            case 'PAYMENT.PAYOUTS-ITEM.FAILED':
            case 'PAYMENT.PAYOUTS-ITEM.BLOCKED':
            case 'PAYMENT.PAYOUTS-ITEM.REFUNDED':
            case 'PAYMENT.PAYOUTS-ITEM.RETURNED':
                static::payoutItemFailed($data['resource']);
                break;
            case 'PAYMENT.PAYOUTS-ITEM.UNCLAIMED':
                break;
        }
        //handle webhook
        Response::success([
            'message' => 'webhook handled'
        ]);
    }

    public static function handleIPNNotifications()
    {
        $dataString = file_get_contents('php://input');

        parse_str($dataString, $data);

        if (!isset($data['txn_type']) || $data['txn_type'] != 'masspay') {
            return;
        }

        $iteration = 1;

        while (isset($data["masspay_txn_id_{$iteration}"])) {
            $transaction_id = $data["masspay_txn_id_{$iteration}"];
            $receiver_email = $data["receiver_email_{$iteration}"];
            $payment_fee = $data["payment_fee_{$iteration}"];
            $status = $data["status_{$iteration}"];
            $mc_gross = $data["mc_gross_{$iteration}"];
            $payment_gross = $data["payment_gross_{$iteration}"];
            $mc_currency = $data["mc_currency_{$iteration}"];
            $unique_id = $data["unique_id_{$iteration}"];
            $ipn_track_id = $data["ipn_track_id"];
            $payment_date = $data["payment_date"];

            //update in DB

            $massPayoutEntry = MassPayout::query()->where("unique_id = %s", [$unique_id])->first();

            if (!empty($massPayoutEntry)) {
                $relay_payout_id = $massPayoutEntry->payout_id;

                MassPayout::query()->update([
                    'masspay_txn_id' => $transaction_id,
                    'receiver_email' => $receiver_email,
                    'payment_fee' => $payment_fee,
                    'status' => $status,
                    'mc_gross' => $mc_gross,
                    'payment_gross' => $payment_gross,
                    'mc_currency' => $mc_currency,
                    'ipn_track_id' => $ipn_track_id,
                    'payment_date' => $payment_date,
                ], ['id' => $massPayoutEntry->id]);

                //if status is changed update the entry in our DB.
                if (strtolower($status) == 'completed') {
                    do_action('rwp_payment_mark_as_succeeded', $relay_payout_id, []);
                }
            }

            $iteration++;
        }
    }

    public static function payoutItemSucceeded($resource)
    {
        $payout_item = $resource['payout_item'];
        $sender_item_id = $payout_item['sender_item_id'];
        $recipient_wallet = $payout_item['recipient_wallet'];
        $status = $resource['transaction_status'];
        $payout_item_id = $resource['payout_item_id'];
        $activity_id = $resource['activity_id'];
        $payout_batch_id = $resource['payout_batch_id'];

        $batch_payout_item = BatchPayoutItem::query()
            ->where("sender_item_id = %s", [$sender_item_id])
            ->first();

        if (!empty($batch_payout_item)) {

            BatchPayoutItem::query()->update([
                'transaction_status' => $status,
                'payout_item_id' => $payout_item_id,
                'activity_id' => $activity_id,
                'payout_batch_id' => $payout_batch_id,
                'receipient_wallet' => $recipient_wallet,
            ], [
                'id' => $batch_payout_item->id
            ]);

            $relay_payout_id = $batch_payout_item->affiliate_payout_id;

            do_action('rwp_payment_mark_as_succeeded', $relay_payout_id, []);
        }


    }

    public static function payoutItemFailed($resource)
    {
        $payout_item = $resource['payout_item'];
        $sender_item_id = $payout_item['sender_item_id'];
        $recipient_wallet = $payout_item['recipient_wallet'];
        $status = $resource['transaction_status'];
        $payout_item_id = $resource['payout_item_id'];
        $activity_id = $resource['activity_id'];
        $payout_batch_id = $resource['payout_batch_id'];

        $batch_payout_item = BatchPayoutItem::query()
            ->where("sender_item_id = %s", [$sender_item_id])
            ->first();

        if (!empty($batch_payout_item)) {

            BatchPayoutItem::query()->update([
                'transaction_status' => $status,
                'payout_item_id' => $payout_item_id,
                'activity_id' => $activity_id,
                'payout_batch_id' => $payout_batch_id,
                'receipient_wallet' => $recipient_wallet,
            ], [
                'id' => $batch_payout_item->id
            ]);

            $relay_payout_id = $batch_payout_item->affiliate_payout_id;

            $wp_relay_payout = Payout::query()
                ->where("id = %d", [$relay_payout_id])
                ->first();

            if (!empty($wp_relay_payout)) {
                Transaction::create([
                    'affiliate_id' => $wp_relay_payout->affiliate_id,
                    'type' => Transaction::CREDIT,
                    'currency' => $batch_payout_item->currency_code,
                    'amount' => $wp_relay_payout->amount,
                    'transactionable_id' => $wp_relay_payout->id,
                    'transactionable_type' => 'payout',
                    'system_note' => "Payout Failed #{$wp_relay_payout->id} so Refunded",

                ]);

                do_action('rwp_payment_mark_as_failed', $relay_payout_id, []);
            }
        }
    }

    public static function createWebhookEntry($data)
    {
        WebhookEvent::query()->create([
            'webhook_id' => $data['id'],
            'create_time' => $data['create_time'],
            'resource_type' => $data['resource_type'],
            'event_type' => $data['event_type'],
            'resource_data' => json_encode($data),
        ]);
    }

    private static function batchSucceeded($resource)
    {
        static::updateBatchDetails($resource);
    }

    private static function batchDenied($resource)
    {
        static::updateBatchDetails($resource);
    }

    private static function updateBatchDetails($resource)
    {
        $batch_header = $resource['batch_header'];
        $sender_batch_header = $batch_header['sender_batch_header'];
        $sender_batch_id = $sender_batch_header['sender_batch_id'];

        $batch = BatchPayout::query()
            ->where("sender_batch_id = %s", [$sender_batch_id])
            ->first();

        if (!empty($batch)) {
            $batch_status = $batch_header['batch_status'];
            $funding_source = $batch_header['funding_source'];
            $payout_batch_id = $batch_header['payout_batch_id'];
            $fees = json_encode($batch_header['fees']);

            BatchPayout::query()->update([
                'payout_batch_id' => $payout_batch_id,
                'batch_status' => $batch_status,
                'funding_source' => $funding_source,
                'fees' => $fees,
            ], ['id' => $batch->id]);
        }
    }


}