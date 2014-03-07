<?php
namespace RP\core;


class Template
{
    public $layout = null;
    /**
     *  render from the last to the first, the result of the after one will fill to the $content of the previous one
     * @var array
     */
    public $layouts = array();
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

    /**
     * set the root layout
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        $this->layouts[0] = is_string($layout) ? array('content' => $layout) : $layout;
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

    public function addLayout($layout)
    {
        if (is_string($layout)) {
            $layout = array('content' => $layout);
        }
        $this->layouts[] = $layout;
    }


    public function render($tpl, $layout = false)
    {
        if ($layout !== false) {
            $this->setLayout($layout);
        }
        foreach ($this->binding as $name => $value) {
            $$name = $value;
        }
        ob_start();
        $path = $this->getTemplateFullPath($tpl);
        include $path;
        $_output = ob_get_contents();
        ob_end_clean();
        $content = $_output;
        // TODO: use reverse iterator
        for ($i = count($this->layouts) - 1; $i >= 0; --$i) {
            $layout = $this->layouts[$i];
            foreach ($layout as $key => $block) {
                $blockPath = $this->getTemplateFullPath($block);
                ob_start();
                include $blockPath;
                $_output = ob_get_contents();
                ob_end_clean();
                $$key = $_output;
            }
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