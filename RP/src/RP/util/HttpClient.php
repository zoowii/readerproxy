<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-4
 * Time: 下午8:17
 */

namespace RP\util;


class HttpClient
{
    /**
     * @param $site
     * @param $url
     * @param string $method
     * @param mixed $params
     * @param bool $ssl
     * @param bool $headers
     * @param int $timeout
     * @return mixed
     */
    public static function fetch_page($site, $url, $method = 'GET', $params = false, $ssl = false, $headers = false, $timeout = 5)
    {
        $ch = curl_init();
        $cookieFile = $site . '_cookiejar.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        curl_setopt($ch, CURLOPT_HTTPGET, $method === 'GET' ? true : false);
        curl_setopt($ch, CURLOPT_POST, $method === 'POST' ? true : false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if (!$result) {
            throw new \Exception(curl_error($ch));
        }
        return $result;
    }
} 