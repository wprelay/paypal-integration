<?php

namespace RelayWP\Paypal\App\Helpers;

use RelayWP\Paypal\App\Services\Request\Response;

class Resource
{
    public static function resource(array $params)
    {
        $response = (new static)->toArray(...$params);

       return Response::success($response);
    }
}