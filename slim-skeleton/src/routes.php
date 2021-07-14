<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Data as Data;

return function (App $app) {
    $container = $app->getContainer();

    //$container->get('renderer')->setLayout("index.php");

    $app->any('/{name}/[{name2}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message 
        $container->get('logger')->info("Slim-Skeleton '/' route");

        $controller = new \App\Controller\Controller($container);
       
        // Render index view
        return $controller->__run($args["name"], $args["name2"]);
    });
};