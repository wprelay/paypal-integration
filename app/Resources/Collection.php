<?php

namespace WPRelay\Paypal\App\Resources;


use WPRelay\Paypal\App\Services\Request\Response;

class Collection
{

    public static function collection($data, $to_browser = true)
    {
        $response = (new static)->toArray(...$data);

        if ($to_browser) {
            return Response::success($response);
        }

        return $response;
    }
}