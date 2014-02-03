<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午9:33
 */

namespace RP\core;
//
//function staticUrlFor($filename)
//{
//    $config = CONFIG;
//    $url = $config['BASE_URL'] . '/static/' . $filename;
//    return $url;
//}


class CController
{
    /**
     * @var \RP\core\Template
     */
    public $template;

    public function __construct()
    {
        $this->template = new Template();
        $this->bind('controller', $this);
        $this->bind('template', $this->template);
        $this->bind('static_url', function ($filename) {
            $config = $_ENV['_CONFIG'];
            $url = $config['BASE_URL'] . '/static/' . $filename;
            return $url;
        });
    }

    public function setLayout($layout)
    {
        return $this->template->setLayout($layout);
    }

    public function render($tpl, $layout = false)
    {
        return $this->template->render($tpl, $layout);
    }

    public function bind($name, $value)
    {
        return $this->template->bind($name, $value);
    }

}