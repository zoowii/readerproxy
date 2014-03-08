<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-8
 * Time: 下午11:16
 */

namespace RP\util;

use RP\core\OOS;

class BaiduUtil extends OOS
{
    private static $oosInstance = null;
    private static $bucket = null;

    private static function loadBucket()
    {
        $config = $_ENV['_CONFIG'];
        $baiduConfig = $config['baidu'];
        self::$bucket = $baiduConfig['bucket'];
    }

    public static function getBucket()
    {
        if (is_null(self::$bucket)) {
            self::loadBucket();
        }
        return self::$bucket;
    }

    private static function createOOSInstance()
    {
        $config = $_ENV['_CONFIG'];
        $baiduConfig = $config['baidu'];
        $appKey = $baiduConfig['app_key'];
        $secretKey = $baiduConfig['secret_key'];
        $bcsHost = 'bcs.duapp.com'; //online
        require_once ROOT_DIR . '/RP/src/RP/libs/bcs/bcs.class.php';
        self::$oosInstance = new \BaiduBCS ($appKey, $secretKey, $bcsHost);
        return self::$oosInstance;
    }

    /**
     * @return \BaiduBCS
     */
    public static function getOOS()
    {
        if (is_null(self::$oosInstance)) {
            self::createOOSInstance();
        }
        return self::$oosInstance;
    }

    public static function createBucket($name)
    {
        $oos = self::getOOS();
        $acl = \BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ;
        $res = $oos->create_bucket($name, $acl);
        return $res;
    }

    public static function createObject($filepath, $path)
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $opt = array();
        $opt ['acl'] = \BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_READ;
        $opt [\BaiduBCS::IMPORT_BCS_LOG_METHOD] = "bs_log";
        $opt ['curlopts'] = array(
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 1800);
        $response = $oss->create_object($bucket, $path, $filepath, $opt);
        return $response;
    }

    public static function getObject($path, $writePath)
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $opt = array(
            'fileWriteTo' => $writePath);
        $response = $oss->get_object($bucket, $path, $opt);
        if ($response->isOK()) {
            return true;
        } else {
//            printResponse ( $response );
            return $response;
        }
    }

    public static function getObjectInfo($path)
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $response = $oss->get_object_info($bucket, $path);
//        printResponse ( $response );
//        var_dump ( $response->header );
        return $response->header;
    }

    public static function generateGetObjectUrl($path, $ipLimit = false)
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $opt = array();
        $opt ["time"] = time() + 3600; //可选，链接生效时间为linux时间戳向后一小时
        if ($ipLimit) {
            $opt ["ip"] = $ipLimit; //可选，限制本链接发起的客户端ip
        }
        $url = $oss->generate_get_object_url($bucket, $path, $opt);
        return $url;
    }

    public static function uploadDirectory($dirPath, $prefix = '/')
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $opt = array(
            "prefix" => $prefix,
            "has_sub_directory" => true,
            \BaiduBCS::IMPORT_BCS_PRE_FILTER => "pre_filter",
            \BaiduBCS::IMPORT_BCS_POST_FILTER => "post_filter");
        $oss->upload_directory($bucket, $dirPath, $opt);
    }

    public static function setObjectMetaInfo($path)
    {
        $oss = self::getOOS();
        $bucket = self::getBucket();
        $meta = array(
            "Content-Type" => \BCS_MimeTypes::get_mimetype("pdf"));
        $res = $oss->set_object_meta($bucket, $path, $meta);
        return $res;
    }

} 