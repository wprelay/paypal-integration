<?php

namespace WPRelay\Paypal\Src\Controllers;

use Error;
use WPRelay\Paypal\App\Helpers\WordpressHelper;
use WPRelay\Paypal\App\Route;
use WPRelay\Paypal\App\Services\Request\Request;
use WPRelay\Paypal\App\Services\Request\Response;

class LocalDataController
{

    public function getLocalData(Request $request)
    {
        try {
            $currentUserData = wp_get_current_user();

            $localData = [
                'plugin_name' => WPR_PAYPAL_PLUGIN_NAME,
                'user' => [
                    'nick_name' => $currentUserData->user_nicename,
                    'email' => $currentUserData->user_email,
                    'url' => $currentUserData->user_url,
                    'is_admin' => $currentUserData->caps['administrator']
                ],
                'nonces' => [
                    'wpr_paypal_nonce' => WordpressHelper::createNonce('paypal_nonce'),
                ],
                'home_url' => get_home_url(),
                'admin_url' => admin_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_name' => Route::AJAX_NAME,
                'version' => WPR_PAYPAL_VERSION,
            ];

            $localize = apply_filters('wpr_local_data', $localData);

            return Response::success($localize);
        } catch (\Exception|Error $exception) {

            return Response::error([
                'message' => 'Unable to Fetch the Local Data'
            ]);
        }
    }

}