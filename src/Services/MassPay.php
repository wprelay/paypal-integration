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

class MassPay
{
    public static function pay()
    {
        error_log("Executing Mass Pay");

        $processBy = 'EmailAddress';

        $data = [
            [
                'mail' => 'johndoe1@gmaill.com',
                'currencyCode' => 'USD',
                'amount' => 10.00,
                'unique_id' => Functions::getUniqueId(),
                'note' => 'Mass Payout'
            ],
            [
                'mail' => 'sb-vru3g30472026@personal.example.com',
                'currencyCode' => 'USD',
                'amount' => random_int(5, 60),
                'unique_id' => Functions::getUniqueId(),
                'note' => 'Mass Payout'
            ],
            [
                'mail' => 'sb-mkrep30470489@personal.example.com',
                'currencyCode' => 'USD',
                'amount' => random_int(1, 50),
                'unique_id' => Functions::getUniqueId(),
                'note' => 'Mass Payout'
            ]
        ];
        /*
         *  # MassPay API
        The MassPay API operation makes a payment to one or more PayPal account
        holders.
        This sample code uses Merchant PHP SDK to make API call
        */
        $massPayRequest = new MassPayRequestType();
        $massPayRequest->MassPayItem = array();

        error_log('Building Mass Payment data');
        foreach ($data as $item) {
            $masspayItem = new MassPayRequestItemType();
            /*
             *  `Amount` for the payment which contains

            * `Currency Code`
            * `Amount`
            */
            $masspayItem->Amount = new BasicAmountType($item['currencyCode'], $item['amount']);
            if ($processBy == 'EmailAddress') {
                /*
                 *  (Optional) How you identify the recipients of payments in this call to MassPay. It is one of the following values:
                EmailAddress
                UserID
                PhoneNumber
                */
                $masspayItem->ReceiverEmail = $item['mail'];
                $masspayItem->UniqueId = $item['unique_id'];
                $masspayItem->Note = $item['note'];
            } else {
                return;
            }
            $massPayRequest->MassPayItem[] = $masspayItem;
        }

        error_log('data builed');

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

        error_log('printing paypal service object');

        error_log(print_r($paypalService, true));

// required in third party permissioning
//        if (($_POST['accessToken'] != null) && ($_POST['tokenSecret'] != null)) {
//            $cred = new PPSignatureCredential("sb-4aqlq29883972_api1.business.example.com", "WX4WTU3S8MY44S7F", "AFcWxV21C7fd0v3bYYYRCpSSRl31A7yDhhsPUU2XhtMoZXsWHFxu-RWy");
//            $cred->setThirdPartyAuthorization(new PPTokenAuthorization($_POST['accessToken'], $_POST['tokenSecret']));
//        }

        try {
            /* wrap API method calls on the service object with a try catch */
//            if (($_POST['accessToken'] != null) && ($_POST['tokenSecret'] != null)) {
//                $massPayResponse = $paypalService->MassPay($massPayReq, null);
//            } else {
                $massPayResponse = $paypalService->MassPay($massPayReq);
//            }
        } catch (\Exception $exception) {
            PluginHelper::logError("Error Occurred while paying mass payment", [__CLASS__, __FUNCTION__], $exception);
        }

        if (isset($massPayResponse)) {
            error_log(print_r($massPayResponse, true));
            return $massPayResponse;
        } else {
            error_log('error occurred while mass payment');
            return false;
        }


    }
}