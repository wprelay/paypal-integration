<?php

//All routes actions will be performed in Route::handleAuthRequest method.

use WPRelay\Paypal\App\Hooks\AdminHooks;

$admin_hooks = [
    'actions' => [
            'admin_init' => ['callable' => [AdminHooks::class, 'init'], 'priority' => 10, 'accepted_args' => 1],
            'admin_head' => ['callable' => [AdminHooks::class, 'head'], 'priority' => 10, 'accepted_args' => 1],
            'admin_menu' => ['callable' => [AdminHooks::class, 'addMenu'], 'priority' => 10, 'accepted_args' => 1],
    ],
    'filters' => [

    ],
];

$store_front_hooks = [
    'actions' => [],
    'filters' => [],
];

return [
    'admin_hooks' => $admin_hooks,
    'store_front_hooks' => $store_front_hooks
];