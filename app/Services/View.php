<?php

namespace WPRelay\Paypal\App\Services;

use WPRelay\Paypal\App\Helpers\Functions;
class View
{
    public static function instance()
    {
        return new static();
    }

    public static function render($path, $data = [])
    {
        return static::instance()->view($path, array_merge(['app' => App::make()], $data));
    }

    public function view($path, $data, $print = true)
    {
        $file = RWP_PLUGIN_PATH. 'resources/' . $path . '.php';
        return Functions::renderTemplate($file, $data);
    }
}