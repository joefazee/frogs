<?php

namespace Core\Controllers;


use Core\Models\Store;

/**
 * Class HomeController
 *
 * @package \Core\Controllers
 */
class HomeController extends Base
{

    public function index()
    {
        $this->view('index.tpl');
    }

    public function test()
    {

    }
}
