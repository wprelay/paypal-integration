<?php

//All routes actions will be performed in Route::handleAuthRequest method.
use WPRelay\Paypal\Src\Controllers\Admin\ListController;
use WPRelay\Paypal\Src\Controllers\Admin\SettingsController;
use WPRelay\Paypal\Src\Controllers\LocalDataController;

return [
    'get_local_data' => ['callable' => [LocalDataController::class, 'getLocalData']],
    'paypal_batch_item_list' => ['callable' => [ListController::class, 'batchPayoutItemList']],
    'paypal_mass_payout_item_list' => ['callable' => [ListController::class, 'massPayoutItemList']],
    'get_paypal_settings' => ['callable' => [SettingsController::class, 'getSettings']],
    'save_paypal_settings' => ['callable' => [SettingsController::class, 'saveSettings']],
];