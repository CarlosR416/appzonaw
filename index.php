<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/slim-skeleton/vendor/autoload.php';

session_start();



// Instantiate the app
$settings = require __DIR__ . '/slim-skeleton/src/settings.php';
$app = new \Slim\App($settings);

//Inicializa la db
$capsule = require __DIR__ . '/slim-skeleton/src/database.php';
$capsule($app);

// Set up dependencies
$dependencies = require __DIR__ . '/slim-skeleton/src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/slim-skeleton/src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/slim-skeleton/src/routes.php';
$routes($app);

// Run app
$app->run();
