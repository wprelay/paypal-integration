<?php

//All routes actions will be performed in Route::handleAuthRequest method.
use WPRelay\Paypal\Src\Controllers\Admin\ListController;
use WPRelay\Paypal\Src\Controllers\LocalDataController;

return [
    'get_local_data' => ['callable' => [LocalDataController::class, 'getLocalData']],
    'paypal_batch_item_list' => ['callable' => [ListController::class, 'batchPayoutItemList']],
];