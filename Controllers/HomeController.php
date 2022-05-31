<?php

namespace App\Controllers;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class HomeController
{
    public function getIndex(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'home.twig');
    }


}
