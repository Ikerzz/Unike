<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';

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

$container['HomeController'] = function ($container) {
        return new \App\Controllers\HomeController;
};

//$test = [
//];
//return  $this->view->render($response, 'home.twig',compact('test'));


//$app->get('/', function ($request, $response, $args) {
//    global $test;
//    return  $this->view->render($response, 'home.twig');
//});


// Rutas

//$app->get('/', Shop\Controllers\HomeController::class . ":getIndex");
$app->get('/', 'HomeController:index');
