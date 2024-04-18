<?php

namespace WPRelay\Paypal\App\Resources;


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