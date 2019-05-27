<?php

namespace Core\Controllers;

use Core\View;

/**
 * Class Base - This is the controller base class. It basically setup the view engine
 *
 * @package \Core\Controllers
 */
class Base
{
    protected $viewEngine;

    protected function view($name, array $args = [])
    {
        $this->viewEngine = View::getViewInstance()->getView();

        $this->viewEngine->assign($args);

        $this->viewEngine->display($name);
    }
}
