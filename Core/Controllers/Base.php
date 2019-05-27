<?php

namespace Core\Controllers;

use Core\Models\Store;
use Core\View;

/**
 * Class Base - This is the controller base class. It basically setup the view engine
 *
 * @package \Core\Controllers
 */
class Base
{
    protected $viewEngine;

    protected $store;

    public function __construct()
    {
        $this->store = new Store();
    }

    protected function view($name, array $args = [])
    {
        $this->viewEngine = View::getViewInstance()->getView();

        $this->viewEngine->assign($args);

        $this->viewEngine->display($name);

    }

    protected function renderJson(array $data, $status=200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
