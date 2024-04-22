<?php

namespace WPRelay\Paypal\Src\Controllers\Webhook;

use WPRelay\Paypal\App\Services\Request\Response;

class PaypalWebhookController
{
    public static function handlePaypalWebhook()
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
    }

    public static function handleWebhook()
    {
        //handle webhook
        Response::success([
            'message' => 'webhook handled'
        ]);
    }
}