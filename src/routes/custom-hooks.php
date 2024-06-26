<?php

use WPRelay\Paypal\Src\Paypal;
use WPRelay\Paypal\Src\PayPalClient;

$store_front_hooks = [
    'actions' => [
        'wpr_process_paypal_payouts' => ['callable' => [Paypal::class, 'sendPayments'], 'priority' => 11, 'accepted_args' => 1],
//        'wpr_currency_is_available_for_paypal_payment' => ['callable' => [Paypal::class, 'isCurrencyAvailableForPayment'], 'priority' => 11, 'accepted_args' => 1],
    ],
    'filters' => [
        'rwp_payment_process_sources' => ['callable' => [Paypal::class, 'addPaypalPayment'], 'priority' => 11, 'accepted_args' => 4],
    ]
];

$admin_hooks = [
    'actions' => [],
    'filters' => [
        'wpr_paypal_payment_available_for_currency' => ['callable' => [PayPalClient::class, 'isCurrencyAvailableForPayment'], 'priority' => 11, 'accepted_args' => 2],
    ]
];

return [
    'store_front_hooks' => $store_front_hooks,
    'admin_hooks' => $admin_hooks
];