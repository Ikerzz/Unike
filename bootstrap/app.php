<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Controllers/HomeController.php';
require __DIR__ . '/../Controllers/SQLController.php';
require __DIR__ . '/../Controllers/MenController.php';



$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../resources/Views', [
        'cache' => false
    ]);

    return $view;
};

// Rutas

//$app->get('/', App\Controllers\HomeController::class . ":getIndex");
$app->get('/', \App\Controllers\HomeController::class . ':getIndex');
$app->get('/bdd', \App\Controllers\HomeController::class . ':getBDD');
$app->get('/men/shirts', \App\Controllers\MenController::class . ':getShirt');
$app->get('/men/shirts/{id}', \App\Controllers\MenController::class . ':getUniqueShirt');
$app->get('/vue', \App\Controllers\MenController::class . ':Vue');



