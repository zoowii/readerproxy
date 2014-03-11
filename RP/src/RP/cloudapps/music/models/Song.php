<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-8
 * Time: 下午11:57
 */

namespace RP\cloudapps\music\models;

use RP\models\CModel;

/**
 * Class Song
 * @package RP\cloudapps\music\models
 * @property string id
 * @property int version
 * @property string created_time
 * @property string source
 * @property string name
 * @property string artist
 * @property string album
 * @property string tmp_url
 * @property string data_store_service
 * @property string data_store_key
 * @property string lyric_tmp_url
 * @property string lyric_store_service
 * @property string lyric_store_key
 * @property string picture_tmp_url
 * @property string picture_store_service
 * @property string picture_store_key
 * @property string status
 */
class Song extends CModel
{
    protected static $tableName = 'song';
    protected static $pkColumn = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->data_store_service = 'baidu_bcs';
        $this->lyric_store_service = 'baidu_bcs';
        $this->picture_store_service = 'baidu_bcs';
        $this->picture_tmp_url = null;
        $this->lyric_tmp_url = null;
        $this->status = 'new';
    }

    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'created_time' => 'datetime',
        'source' => 'string', // 来源，比如虾米网，豆瓣音乐，百度音乐，用户上传等
        'name' => 'string',
        'artist' => 'string',
        'album' => 'string',
        'tmp_url' => 'string', // 暂时可用的获取音乐文件的URL，不一定一直可用（仅供临时后台下载用)
        'data_store_service' => 'string', // 存储服务，比如百度云存储(baidu_bcs)，或者文件硬盘存储
        'data_store_key' => 'string', // 可以用来唯一标示在存储服务的字符串，可以是序列话后的json格式。或者任何其他的。比如{bucket: 'xxx', path: 'xxx'}
        'lyric_tmp_url' => 'string',
        'lyric_store_service' => 'string',
        'lyric_store_key' => 'string',
        'picture_tmp_url' => 'string',
        'picture_store_service' => 'string',
        'picture_store_key' => 'string',
        'status' => 'string',
    );

    public static function createOrUpdate($id, $source, $name, $artist, $album, $tmp_url, $data_store_service, $data_store_key, $lyric_tmp_url, $lyric_store_service, $lyric_store_key, $picture_tmp_url, $picture_store_service, $picture_store_key, $status = false)
    {
        $song = Song::findByPk($id);
        if (is_null($song)) {
            $song = new Song();
            $song->id = $id;
            $song->status = 'new';
        }
        $song->source = $source;
        $song->name = $name;
        $song->artist = $artist;
        $song->album = $album;
        $song->tmp_url = $tmp_url;
        $song->lyric_tmp_url = $lyric_tmp_url;
        $song->picture_tmp_url = $picture_tmp_url;
        if (!empty($data_store_service)) {
            $song->data_store_service = $data_store_service;
        }
        $song->data_store_key = $data_store_key;
        if (!empty($lyric_store_service)) {
            $song->lyric_store_service = $lyric_store_service;
        }
        $song->lyric_store_key = $lyric_store_key;
        if (!empty($picture_store_service)) {
            $song->picture_store_service = $picture_store_service;
        }
        $song->picture_store_key = $picture_store_key;
        if (is_string($status)) {
            $song->status = $status;
        }
        $song->saveOrUpdate();
        return $song;
    }
} 