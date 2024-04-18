<?php

namespace WPRelay\Paypal\App\Helpers;

defined('ABSPATH') or exit;
defined('ABSPATH') or exit;
class WordpressHelper
{

    /**
     * Verify nonce
     *
     * @param string $nonce
     * @param string $action
     * @return false
     */
    public static function verifyNonce($key, $nonce)
    {
        return (bool)wp_verify_nonce($nonce, $key);
    }

    /**
     * Verify nonce
     *
     * @param string $nonce
     * @param string $action
     * @return false
     */
    public static function createNonce($action)
    {
        return wp_create_nonce($action);
    }

    /**
     * Format date
     *
     * @param string|int $date
     * @param string $format
     * @param bool $is_gmt
     * @return string
     */
    public static function formatDate($date, $format = 'date', $is_gmt = false)
    {
        if (is_numeric($date)) {
            $date = date('Y-m-d H:i:s', $date);
        }
        if (in_array($format, ['datetime', 'date', 'time'])) {
            $format = self::getFormat($format);
        }
        return $is_gmt ? get_date_from_gmt($date, $format) : date($format, strtotime($date));
    }

    public static function getCurrentURL()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = wp_unslash($_SERVER['HTTP_HOST']); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        } else {
            $host = wp_parse_url(home_url(), PHP_URL_HOST);
        }
        if (isset($_SERVER['REQUEST_URI'])) {
            $path = wp_unslash($_SERVER['REQUEST_URI']); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        } else {
            $path = '/';
        }
        return esc_url_raw((is_ssl() ? 'https' : 'http') . '://' . $host . $path);
    }


    public static function generateRandomString($length = 10) {

        return substr(md5(time()), 0, $length);
    }

}
