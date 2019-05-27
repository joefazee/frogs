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

    /**
     * Endpoint to load and create frogs
     *
     * @param null $id
     */
    public function frogs()
    {
        if($this->methodIs('post'))
        {
           $weight  = $this->posts('weight');
           $color = $this->posts('color');
           $batch = $this->posts('batch');
           $group_id = $this->posts('group_id');
           $id = $this->posts('id');

           if(!$weight || !$color || !$group_id)
           {
               $this->renderJson(['status' => 'error', 'message' => 'Weight, Color and Group is required']);
           }

          if($id)
          {
            $this->store->updateFrog($id, $weight, $color, $batch, $group_id);
          }
          else
          {
              $this->store->createFrog($weight, $color, $batch, $group_id);
          }

           $this->renderJson(['status' => 'ok']);

        }
        else
        {
            // render frogs
            $this->renderJson(['status' => 'ok', 'data' => $this->store->getAllFrogs()]);
        }
    }

    public function deleteFrog($id)
    {
        $this->store->deleteFrogById($id);

        $this->renderJson(['status' => 'ok']);
    }
}
