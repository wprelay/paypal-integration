<?php

namespace WPRelay\Paypal\App\Helpers;

use WPRelay\Paypal\App\Services\Request\Response;

class Resource
{
    public static function resource(array $params)
    {
        $response = (new static)->toArray(...$params);

       return Response::success($response);
    }
}