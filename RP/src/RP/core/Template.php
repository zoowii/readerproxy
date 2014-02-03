<?php
namespace RP\core;


class Template
{
    public $layout = null;
    public $binding = array();
    public $templateDir;

    public function __construct()
    {
        $this->templateDir = ROOT_DIR . '/templates';
    }

    public function getTemplateFullPath($tpl)
    {
        return "$this->templateDir/$tpl";
    }

    public function setTemplateDir($dir)
    {
        $this->templateDir = $dir;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function bind($name, $value)
    {
        $this->binding[$name] = $value;
    }

    /**
     * 在模板里包括另外一个模板
     * @param $tpl
     */
    public function includeTemplate($tpl)
    {
        $path = $this->getTemplateFullPath($tpl);
        include $path;
    }


    public function render($tpl, $layout = false)
    {
        if ($layout !== false) {
            $this->layout = $layout;
        }
        foreach ($this->binding as $name => $value) {
            $$name = $value;
        }
        ob_start();
        $path = $this->getTemplateFullPath($tpl);
        include $path;
        $_output = ob_get_contents();
        ob_end_clean();
        if ($this->layout !== null) {
            $layoutPath = $this->getTemplateFullPath($this->layout);
            $content = $_output;
            ob_start();
            include $layoutPath;
            $_output = ob_get_contents();
            ob_end_clean();
        }
        return $_output;
    }

    public function renderPartial($tpl)
    {
        foreach ($this->binding as $name => $value) {
            $$name = $value;
        }
        ob_start();
        $path = $this->getTemplateFullPath($tpl);
        include $path;
        $_output = ob_get_contents();
        ob_end_clean();
        return $_output;
    }

    /**
     * 把一个partial模板渲染的结果放到binding中，方便在layout中加入多个子模板，比如header, footer等
     * @param $tpl
     * @param $name
     * @return string
     */
    public function renderPartialToBinding($tpl, $name)
    {
        $_output = $this->renderPartial($tpl);
        $this->bind($name, $_output);
        return $_output;
    }
}