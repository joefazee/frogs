<?php

namespace Core\Controllers;

use Core\View;

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
}
