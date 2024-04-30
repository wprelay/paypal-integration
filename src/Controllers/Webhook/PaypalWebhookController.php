<?php

namespace WPRelay\Paypal\Src\Controllers\Webhook;

use WPRelay\Paypal\App\Helpers\Functions;
use WPRelay\Paypal\App\Services\Request\Response;
use WPRelay\Paypal\Src\Models\BatchPayout;
use WPRelay\Paypal\Src\Models\BatchPayoutItem;
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
            array(
                array(
                    'methods' => 'POST',
                    'callback' => [__CLASS__, 'handleWebhook'],
                ),
            )
        );

        register_rest_route(
            'ipn/notifications',
            '/paypal',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => [__CLASS__, 'handleIPNNotifications'],
                ),
            )
        );

        error_log('Rest route registered');

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
            case 'PAYMENT.PAYOUTS-ITEM.UNCLAIMED':
                static::payoutItemFailed($data['resource']);
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

        parse_str($dataString,$data);

        error_log('Printing IPN Notifications messages');
        error_log(print_r($data, true));
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

            Payout::query()->update([
                'status' => strtolower($status)
            ], ['id' => $relay_payout_id]);
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

                Payout::query()->update([
                    'status' => 'failed'
                ], ['id' => $relay_payout_id]);
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