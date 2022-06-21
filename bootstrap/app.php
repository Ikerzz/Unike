<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
$path = "/../Controllers/";

require __DIR__ . $path . 'HomeController.php';
require __DIR__ . $path . 'SQLController.php';
require __DIR__ . $path . 'MenController.php';
require __DIR__ . $path . 'WomenController.php';
require __DIR__ . $path . 'PanelController.php';
require __DIR__ . $path . 'Session.php';
require __DIR__ . $path . 'Product.php';




$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);


$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../resources/Views', [
        'cache' => false,
        'auto_reload' => true
    ]);

    $view->getEnvironment()->addGlobal('session',$_SESSION);

    return $view;
};

$container['session'] = function($container) {
    $session = new \App\Controllers\Session();
    return $session;
};

// Ruta para test

$app->get('/test', \App\Controllers\SQLController::class . ':getProductsUser');
$app->get('/kill', \App\Controllers\PanelController::class . ':killSesion');


// Ruta Index

$app->get('/', \App\Controllers\HomeController::class . ':getIndex');


// Ruta Conocenos

$app->get('/knowUs', \App\Controllers\KnowUsController::class . ':getIndex');


// Rutas Loguearse/Registrarse


$app->get('/register', \App\Controllers\PanelController::class . ':getRegister');
$app->post('/registerUser', \App\Controllers\PanelController::class . ':registerUser');
$app->get('/login', \App\Controllers\PanelController::class . ':getIndex');

// Rutas Panel usuario

$app->post('/panel', \App\Controllers\PanelController::class . ':getUser');
$app->get('/panel', \App\Controllers\PanelController::class . ':getPanel');
$app->get('/panel/products', \App\Controllers\PanelController::class . ':getPanelProducts');


// Rutas para Catalogo

// Hombre
$app->get('/men/shirts', \App\Controllers\MenController::class . ':getShirts');
$app->get('/men/shirts/{id}', \App\Controllers\Product::class . ':getUniqueProduct');

$app->get('/men/pants', \App\Controllers\MenController::class . ':getPants');
$app->get('/men/pants/{id}', \App\Controllers\Product::class . ':getUniqueProduct');

$app->get('/men/footwear', \App\Controllers\MenController::class . ':getFootWear');
$app->get('/men/footwear/{id}', \App\Controllers\Product::class . ':getUniqueProduct');

// Mujer

$app->get('/women/shirts', \App\Controllers\WomenController::class . ':getShirts');
$app->get('/women/shirts/{id}', \App\Controllers\Product::class . ':getUniqueProduct');

$app->get('/women/pants', \App\Controllers\WomenController::class . ':getPants');
$app->get('/women/pants/{id}', \App\Controllers\Product::class . ':getUniqueProduct');

$app->get('/women/footwear', \App\Controllers\WomenController::class . ':getFootWear');
$app->get('/women/footwear/{id}', \App\Controllers\Product::class . ':getUniqueProduct');
