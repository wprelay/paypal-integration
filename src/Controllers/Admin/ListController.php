<?php

namespace WPRelay\Paypal\Src\Controllers\Admin;

use Error;
use WPRelay\Paypal\App\Helpers\PluginHelper;
use WPRelay\Paypal\App\Resources\BatchPayoutItemCollection;
use WPRelay\Paypal\App\Services\Request\Request;
use WPRelay\Paypal\App\Services\Request\Response;
use WPRelay\Paypal\Src\Models\BatchPayoutItem;

class ListController
{

    public static function batchPayoutItemList(Request $request)
    {
        try {
            $perPage = $request->get('per_page') ? $request->get('per_page') : 10;
            $currentPage = $request->get('current_page') ? $request->get('current_page'): 1;
            $search = $request->get('search', null);

            $query = BatchPayoutItem::query()->select();

            $totalCount = $query->count();

            $items = $query->limit($perPage)
                ->offset(($currentPage - 1) * $perPage)
                ->get();

            BatchPayoutItemCollection::collection([$items, $totalCount, $perPage, $currentPage]);
       } catch (\Exception|Error $exception) {
            PluginHelper::logError('Error Occurred While Processing', [__CLASS__, __FUNCTION__], $exception);
            return Response::error();
        }
    }
}