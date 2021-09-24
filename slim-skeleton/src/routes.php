<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Data as Data;

return function (App $app) {
    $container = $app->getContainer();
    
    //$container->get('renderer')->setLayout("index.php");
    $app->get("/", function (Request $request, Response $response, array $args) use ($container){
        $controller = new \App\Controller\Controller($container);

        return $controller->__run(false, false);
    })->setName('base');

    
    $app->any("/noautorizado", function (Request $request, Response $response, array $args) use ($container){
        
        echo json_encode([['error' => 'No estas autorizado']]);
            
    })->setName('noautorizado');

    $app->any("/login[/{name}]", function (Request $request, Response $response, array $args) use ($container){

        $controller = new \App\Controller\Controller($container);
        
        if(isset($args["name"])){
            // Render index view
           
            return $controller->__run("AuthController", $args["name"]);

        }else{

            return $container->twig->render($container->response,  "login.twig");

        }
            
    })->setName('login');

    $app->any('/data/{arg1}[/{arg2}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message 
        
        $container->get('logger')->info("Slim-Skeleton '/' route");

        $controller = new \App\Controller\Controller($container);
       
        // Render index view
        return $controller->__run('data', $args["arg1"], $args);

    })->setName('data')->add("MenuMiddleware")->add("AuthMiddleware");

    $app->any('/{arg1}/{arg2}', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message 

        $container->get('logger')->info("Slim-Skeleton '/' route");

        $controller = new \App\Controller\Controller($container);
       
        // Render index view
        return $controller->__run($args["arg1"], $args["arg2"]);
    })->setName('ruta')->add("MenuMiddleware")->add("AuthMiddleware");

    


    
};
