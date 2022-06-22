<?php

namespace App\Controllers;

use App\Controllers\SQLController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Legal
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
    }

    public function getIndex(Request $request, Response $response, $args)
    {
        return $this->view->render($response, '/legal/index.twig');
    }

}
