<?php

use WPRelay\Paypal\Src\Controllers\Webhook\PaypalWebhookController;

$store_front_hooks = [
    'actions' => [
        'rest_api_init' => ['callable' => [PaypalWebhookController::class, 'handlePaypalWebhook'], 'priority' => 10, 'accepted_args' => 4],
//        add_action( 'rest_api_init', 'wk_register_custom_routes' );
    ],
    'filters' => [],
];

$admin_hooks = [
    'actions' => [],
    'filters' => [],
];

return [
    'store_front_hooks' => $store_front_hooks,
    'admin_hooks' => $admin_hooks
];