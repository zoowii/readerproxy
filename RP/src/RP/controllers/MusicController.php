<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午10:47
 */

namespace RP\controllers;


use RP\cloudapps\music\models\Song;
use RP\util\BaiduUtil;
use RP\util\HttpClient;
use RP\util\TmpFsUtil;

class MusicController extends BaseController
{

    public function playerAction()
    {
        return $this->render('tools/music_player.php');
    }

    public function asyncStoreAction()
    {
        $guid = $this->args('guid');
        $host = $_SERVER['SERVER_NAME'];
        HttpClient::asyncLoad('server', 'http://' . $host . '/index.php/music/store?guid=' . $guid);
        return $this->ajaxSuccess('已经提交请求');
    }

    public function storeAction()
    {
        $guid = $this->args('guid');
        $song = Song::findByPk($guid);
        if (is_null($song)) {
            return $this->ajaxFail("Can't find song $guid");
        }
        if ($song->status === 'done') {
            return $this->ajaxFail("This song has downloaded");
        }
        if ($song->status === 'downloading') {
            return $this->ajaxFail('This song is downloading');
        }
        $tmp_url = trim($song->tmp_url);
        if (is_null($song->tmp_url) || strlen($tmp_url) < 1) {
            return $this->ajaxFail("The song has not download url yet");
        }
        // 现在只支持百度云存储
        // store music file
        $data_path = json_decode($song->data_store_key)->object;
        $data_filepath = TmpFsUtil::generateRandomTmpFilepath();
        $song->status = 'downloading';
        $song->saveOrUpdate();
        $data = HttpClient::fetch_page('server', $tmp_url, 'GET', null, false, false, 120);
        $file = fopen($data_filepath, 'w');
        fwrite($file, $data);
        fclose($file);
        BaiduUtil::createObject($data_filepath, $data_path);
        $song->status = 'done';
        $song->saveOrUpdate();
        unlink($data_filepath);
        // store lyric
        if (!is_null($song->lyric_store_key) && !empty($song->lyric_tmp_url)) {
            $lyric_tmp_url = $song->lyric_tmp_url;
            $lyric_path = json_decode($song->lyric_store_key)->object;
            $lyric_filepath = TmpFsUtil::generateRandomTmpFilepath();
            $data = HttpClient::fetch_page('server', $lyric_tmp_url, 'GET', null, false, false, 5);
            $file = fopen($lyric_filepath, 'w');
            fwrite($file, $data);
            fclose($lyric_filepath);
            BaiduUtil::createObject($lyric_filepath, $lyric_path);
            unlink($lyric_filepath);
        }
        // store picture
        if (!is_null($song->picture_store_key) && !empty($song->picture_tmp_url)) {
            $picture_tmp_url = $song->picture_tmp_url;
            $picture_path = json_decode($song->picture_store_key)->object;
            $picture_filepath = TmpFsUtil::generateRandomTmpFilepath();
            $data = HttpClient::fetch_page('server', $picture_tmp_url, 'GET', null, false, false, 5);
            $file = fopen($picture_filepath, 'w');
            fwrite($file, $data);
            fclose($picture_filepath);
            BaiduUtil::createObject($picture_filepath, $picture_path);
            unlink($picture_filepath);
        }
        return $this->ajaxSuccess('done');
    }

    public function testUploadFileToBcs()
    {
        $filepath = TmpFsUtil::generateRandomTmpFilepath('a.txt');
        BaiduUtil::createObject($filepath, '/a.txt');
        return 'done';
    }

} 