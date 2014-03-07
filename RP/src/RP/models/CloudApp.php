<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午9:26
 */

namespace RP\models;

/**
 * 暂时只手动记录有哪些应用
 * Class CloudApp
 * @package RP\models
 * @property string app_id
 * @property string name
 * @property string description
 * @property string category
 * @property string url
 * @property string target
 */
class CloudApp extends CModel
{
    protected static $tableName = 'cloud_app';
    protected static $pkColumn = 'id';
    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'created_time' => 'datetime',
        'app_id' => 'string', // 唯一标识ID，并且是有意义的唯一ID
        'name' => 'string',
        'description' => 'string',
        'category' => 'string',
        'url' => 'string',
        'target' => 'string', // _blank, _current, _frame, or other, means where to open the app
    );

    public static $UNCATEGORY_NAME = 'other';

    public function __construct()
    {
        $this->description = '';
        $this->category = null;
    }

    public static function getAll()
    {
        $cloudApps = array();

        // 系统应用
        $overviewApp = new CloudApp();
        $overviewApp->app_id = 'system_overview';
        $overviewApp->name = '概览';
        $overviewApp->category = 'system';
        $overviewApp->url = '/';
        $overviewApp->target = '_current';
        $cloudApps[] = $overviewApp;

        $novelApp = new CloudApp();
        $novelApp->app_id = 'tool_novel';
        $novelApp->name = '小说搜索';
        $novelApp->description = '搜索下载和在线阅读小说';
        $novelApp->category = 'life';
        $novelApp->url = '/index.php/sites/qishu';
        $novelApp->target = '_current';
        $cloudApps[] = $novelApp;

        $musicApp = new CloudApp();
        $musicApp->app_id = 'tool_music';
        $musicApp->name = '音乐搜索';
        $musicApp->description = '搜索下载和在线听音乐';
        $musicApp->url = '/index.php/sites/xiami';
        $musicApp->category = 'life';
        $musicApp->target = '_current';
        $cloudApps[] = $musicApp;

        $stockApp = new CloudApp();
        $stockApp->app_id = 'tool_stockkit';
        $stockApp->name = '股票查看';
        $stockApp->description = '方便搜索和查看股票行情，将来可能加入监控提醒分析功能';
        $stockApp->url = 'http://stockkit.sinaapp.com/';
        $stockApp->category = 'tool';
        $stockApp->target = '_blank';
        $cloudApps[] = $stockApp;
        return $cloudApps;
    }

    public static function getAllGroupByCategory()
    {
        $cloudApps = self::getAll();
        $res = array(
            self::$UNCATEGORY_NAME => array()
        );
        foreach ($cloudApps as $cloudApp) {
            $category = $cloudApp->category;
            if ($category) {
                $category = trim($category);
                if (!isset($res[$category])) {
                    $res[$category] = array();
                }
                $res[$category][] = $cloudApp;
            }
        }
        if (count($res[self::$UNCATEGORY_NAME]) < 1) {
            unset($res[self::$UNCATEGORY_NAME]);
        }
        return $res;
    }

    public function getLinkTarget()
    {
        switch ($this->target) {
            case '_blank':
            {
                return '_blank';
            }
                break;
            case '_current':
            case '_self':
            {
                return '_self';
            }
                break;
            default:
                {
                return '_self';
                }
        }
    }

} 