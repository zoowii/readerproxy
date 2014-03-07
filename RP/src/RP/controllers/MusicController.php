<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午10:47
 */

namespace RP\controllers;


class MusicController extends BaseController
{

    public function playerAction()
    {
        return $this->render('tools/music_player.php');
    }

} 