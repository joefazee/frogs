<?php

namespace Core\Controllers;

/**
 * Class Api
 *
 * @package \Core\Controllers
 */
class ApiController extends Base
{

    public function groups()
    {
        $this->renderJson(['status' => 'ok', 'data' => $this->store->getAllGroups()]);
    }

    public function frogs()
    {
        $this->renderJson(['status' => 'ok', 'data' => $this->store->getAllFrogs()]);
    }
}
