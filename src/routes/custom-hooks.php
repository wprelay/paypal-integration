<?php

use WPRelay\Paypal\Src\Paypal;

$store_front_hooks = [
    'actions' => [
    ],
    'filters' => [
        'rwp_payment_process_sources' => ['callable' => [Paypal::class, 'addPaypalPayment'], 'priority' => 10, 'accepted_args' => 4],
    ]
];

$admin_hooks = [
    'actions' => [],
    'filters' => [

    ]
];

return [
    'store_front_hooks' => $store_front_hooks,
    'admin_hooks' => $admin_hooks
];