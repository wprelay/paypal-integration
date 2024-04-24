<?php

namespace WPRelay\Paypal\App\Helpers;

defined('ABSPATH') or exit;

use DateTime;
use DateTimeZone;

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

    public static function utcToWPTime($datetime, $format = 'Y-m-d H:i:s')
    {
        if (empty($datetime)) return null;

        $date = new DateTime($datetime, new DateTimeZone('UTC'));

        $timestamp = $date->format('U');

        return wp_date($format, $timestamp);

    }

    public static function getWcTime($datetime, $format = 'Y-m-d H:i:s')
    {
        return static::utcToWPTime($datetime, $format);
    }

    public static function currentUTCTime($format = 'Y-m-d H:i:s')
    {
        return current_datetime()->setTimezone(new DateTimeZone('UTC'))->format($format);
    }
}