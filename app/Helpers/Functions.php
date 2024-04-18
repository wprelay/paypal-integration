<?php

namespace WPRelay\Paypal\App\Helpers;

defined('ABSPATH') or exit;

class Functions
{
    //code

    public static function renderTemplate($file, $data = [])
    {
        error_log($file);
        if (file_exists($file)) {
            ob_start();
            extract($data);
            include $file;
            return ob_get_clean();
        }
        return false;
    }
}