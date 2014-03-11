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
    public static function fetch_page($site, $url, $method = 'GET', $params = false, $ssl = false, $headers = false, $timeout = 10)
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

    /**
     * 异步加载。如果要执行异步任务，可以先定义个action url，在里面实习同步阻塞的调用。然后用这个方式来异步调用那个页面
     * @param $site
     * @param $url
     * @param string $method
     * @param bool $params
     * @param bool $ssl
     * @param bool $headers
     * @param int $timeout
     */
    public static function asyncLoad($site, $url, $method = 'GET', $params = false, $ssl = false, $headers = false, $timeout = 5)
    {
//        foreach ($get_params as $key => &$val) {
//            if (is_array($val)) $val = implode(',', $val);
//            $post_params[] = $key . '=' . urlencode($val);
//        }
        if ($params) {
            $post_string = implode('&', $params);
        } else {
            $post_string = '';
        }
        $parts = parse_url($url);
        $fp = fsockopen($parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno, $errstr, 30);
        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

} 