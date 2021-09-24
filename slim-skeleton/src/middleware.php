<?php

use Slim\App;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);
    $container = $app->getContainer();
    
    $container['AuthMiddleware'] = function ($container)
    {
        return new \App\Middleware\AuthMiddleware($container);
    };

    $container['MenuMiddleware'] = function ($container)
    {
        return new \App\Middleware\MenuMiddleware($container);
    };

    $app->add(function (Request $request, Response $response, callable $next) {
       
        $uri = $request->getUri();
        $path = $uri->getPath();
        if ($path != '/' && substr($path, -1) == '/') {
            // recursively remove slashes when its more than 1 slash
            while(substr($path, -1) == '/') {
                $path = substr($path, 0, -1);
            }

            // permanently redirect paths with a trailing slash
            // to their non-trailing counterpart
            $uri = $uri->withPath($path);
            
            if($request->getMethod() == 'GET') {
                return $response->withRedirect((string)$uri, 301);
            }
            else {
                return $next($request->withUri($uri), $response);
            }
        }

        return $next($request, $response);
    });

};
