<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    $container['data'] = function ($c) {
        
        return new \App\Data($c);

    };

    $container['DataMikrotick'] = function ($c) {
        
        return new \App\Helpers\DataMikrotick($c);

    };
    
    
    $container['twig'] = function ($c) {
        $settings = $c->get('settings')['renderer'];

        $view = new \Slim\Views\Twig($settings['template_path']);
    
        // Instantiate and add Slim specific extension
        $router = $c->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    
        return $view;
    };

    $container["notFoundHandler"] = function ($c) {

        return function ($request, $response) use ($c) {
            return $c['twig']->render($response,  "page_404.twig");

        };
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
};
