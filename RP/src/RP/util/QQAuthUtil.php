<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-19
 * Time: 下午10:24
 */

namespace RP\util;


class QQAuthUtil
{
    public static function getQQAppId()
    {
        $config = $_ENV['_CONFIG'];
        $appId = $config['qq']['app_id'];
        return $appId;
    }

    public static function getQQAppKey()
    {
        $config = $_ENV['_CONFIG'];
        $appKey = $config['qq']['app_key'];
        return $appKey;
    }

    public static function getAuthUrl()
    {
        $appId = self::getQQAppId();
        $scopes = 'get_user_info';
        $redirectUrl = Common::getHost() . '/index.php/auth/callback/qq';
        $redirectUrl = urlencode($redirectUrl);
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id=$appId&redirect_uri=$redirectUrl&scope=$scopes";
        return $url;
    }


    public static function getUserInfo($access_token, $openid)
    {
        $url = 'https://graph.qq.com/user/get_user_info';
        $params = http_build_query(array(
            'oauth_consumer_key' => self::getQQAppId(),
            'access_token' => $access_token,
            'openid' => $openid,
            'format' => 'json'
        ));
        $url .= "?$params";
        $res = file_get_contents($url);
        $json = json_decode($res);
        return $json;
    }

} 