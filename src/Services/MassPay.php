<?php

namespace WPRelay\Paypal\Src\Services;

use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\PayPalAPI\MassPayReq;
use PayPal\PayPalAPI\MassPayRequestItemType;
use PayPal\PayPalAPI\MassPayRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
use PayPal\Auth\PPSignatureCredential;
use PayPal\Auth\PPTokenAuthorization;
use WPRelay\Paypal\App\Helpers\Functions;
use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\Src\Models\MassPayout;

class MassPay
{
    public static function processPayout($data)
    {
        $payout_data = [];

        foreach ($data as $payment) {
            $payout_data[] = [
                'mail' => $payment['affiliate_email'],
                'currencyCode' => $payment['currency'],
                'amount' => $payment['commission_amount'],
                'unique_id' => PluginHelper::getPayoutIdWithUniqueId($payment['affiliate_payout_id']),
                'payout_id' => $payment['affiliate_payout_id'],
                'affiliate_id' => $payment['affiliate_id'],
            ];
        }

        [$status, $batch_id] = static::pay($payout_data);

        if (empty($status)) {
            MassPayout::query()->update([
                'status' => 'failed'
            ], ['custom_batch_id' => $batch_id]);
        }

        return $status;
    }

    public static function pay($data)
    {
        /*
         *  # MassPay API
        The MassPay API operation makes a payment to one or more PayPal account
        holders.
        This sample code uses Merchant PHP SDK to make API call
        */

        $massPayRequest = new MassPayRequestType();
        $massPayRequest->MassPayItem = array();


        $batch_id = Functions::getUniqueId(13);

        foreach ($data as $item) {

            $masspayItem = new MassPayRequestItemType();
            $masspayItem->Amount = new BasicAmountType($item['currencyCode'], $item['amount']);

            $masspayItem->ReceiverEmail = $item['mail'];
            $masspayItem->UniqueId = $item['unique_id'];

            $massPayRequest->MassPayItem[] = $masspayItem;

            MassPayout::query()->create([
                'receiver_email' => $item['mail'],
                'custom_batch_id' => $batch_id,
                'payout_id' => $item['payout_id'],
                'affiliate_id' => $item['affiliate_id'],
                'unique_id' => $item['unique_id'],
                'payment_gross' => $item['amount'],
                'mc_currency' => $item['currencyCode'],
                'status' => 'Pending'
            ]);
        }

        /*
         *  ## MassPayReq
        Details of each payment.
        `Note:
        A single MassPayRequest can include up to 250 MassPayItems.`
        */
        $massPayReq = new MassPayReq();
        $massPayReq->MassPayRequest = $massPayRequest;

        /*
         * 	 ## Creating service wrapper object
        Creating service wrapper object to make API call and loading
        Configuration::getAcctAndConfig() returns array that contains credential and config parameters
        */

        $paypalService = new PayPalAPIInterfaceServiceService(Configuration::getAcctAndConfig());

        try {
            $massPayResponse = $paypalService->MassPay($massPayReq);

            if (isset($massPayResponse)) {
                $ack = strtolower($massPayResponse['Ack']);
                $statusword = "success";

                if (strpos($ack, $statusword) !== false) {
                    MassPayout::query()->update(['correlation_id' => $massPayResponse['CorrelationID']], ['custom_batch_id' => $batch_id, 'status' => 'processing']);

                    return [$massPayResponse, $batch_id];
                } else {
                    return [false, $batch_id];
                }
            } else {
                return [false, $batch_id];
            }
        } catch (\Exception $exception) {
            PluginHelper::logError("Error Occurred while paying mass payment", [__CLASS__, __FUNCTION__], $exception);
            return [false, $batch_id];
        }
    }
}