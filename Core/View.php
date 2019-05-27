<?php

namespace Core;

/**
 * Class View
 *
 * @package \Core
 */
class View
{
    private static $instance = null;

    private $basePath = __DIR__;

    private $templatePath = '/views';

    private $compiledTemplatePath = '/views_compiled';

    private $templateCachePath = '/views_cache';

    private $smarty;

    public function __construct()
    {
        $this->smarty = new \Smarty();

        $this->basePath =  dirname($this->basePath, 1);
        $this->smarty->setTemplateDir($this->basePath . $this->templatePath);
        $this->smarty->setCompileDir($this->basePath . $this->compiledTemplatePath);
        $this->smarty->setCacheDir($this->basePath . $this->templateCachePath);
    }

    public static function getViewInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function getView()
    {
        return $this->smarty;
    }
}
