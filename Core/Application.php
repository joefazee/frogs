<?php

namespace Core;



/**
 * Class Application
 *
 * The application class is responsible for booting the app
 * and routing.
 *
 *
 * @package \Core
 */
class Application {

    private $controllerNameSpace = 'Core\Controllers\\';

    private $defaultController = 'home';  // homeController

    private $defaultAction = 'index'; // homeController::index()

    /**
     * This methods is responsible for registering all components
     */
   public static function serve()
   {

       try
       {
           (new Application())
                ->parseEnvs()
               ->handleControllers();
       }
       catch (\Exception $e)
       {
            // render 500 internal server error page
           echo $e->getMessage();
           exit;
       }

   }


   public function handleControllers()
   {
       $url = trim($_SERVER['REQUEST_URI'], '/');
       $parts = explode('/', $url);
       $action = $this->defaultAction;
       if($url === "" && count($parts) <= 1)
       {
           $controllerName = $this->defaultController;
       }
       else
       {
           $controllerName =  array_shift($parts);
           $action = count($parts) > 0 ?  array_shift($parts) : $this->defaultAction;
       }

       $controller = $this->controllerNameSpace . $controllerName.'Controller';
       if(class_exists($controller))
       {
           $controllerInstance = new $controller;
           return call_user_func_array([$controllerInstance, $action], $parts);
       }

       throw new \Exception('Controller Not Found');

   }

   public function init()
   {

   }

   public function parseEnvs()
   {
       $envFile = dirname(__DIR__, 1) . '/.env';
       if(!file_exists($envFile))
       {
           throw new \Exception('.env not found. Copy .env.example into .env and update db settings');
       }

       $lines = explode(PHP_EOL, file_get_contents($envFile));
       foreach ($lines as $line)
       {
           $line = trim($line);
           if(strlen($line))
           {
               $parts = explode('=', $line);
               if(count($parts) >= 2)
               {
                   putenv(sprintf("%s=%s", array_shift($parts), trim(array_shift($parts), '"')));
               }
           }
       }

       return $this;
   }

}
