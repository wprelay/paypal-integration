<?php

use RelayWp\Affiliate\Pro\Controllers\Admin\Hooks\CommissionTierController;
use RelayWp\Affiliate\Pro\Controllers\Admin\Hooks\OrderController;
use RelayWp\Affiliate\Pro\ValidationRequest\ProgramRequest;

$store_front_hooks = [
    'actions' => [
    ],
    'filters' => [

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