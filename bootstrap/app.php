<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Controllers/HomeController.php';



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


//$test = [
//];
//return  $this->view->render($response, 'home.twig',compact('test'));


//$app->get('/', function ($request, $response, $args) {
//    global $test;
//    return  $this->view->render($response, 'home.twig');
//});


// Rutas

//$app->get('/', App\Controllers\HomeController::class . ":getIndex");
$app->get('/', \App\Controllers\HomeController::class . ':getIndex');
$app->get('/bdd', \App\Controllers\HomeController::class . ':getBDD');

