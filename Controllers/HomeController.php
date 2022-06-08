<?php

namespace App\Controllers;

use App\Controllers\SQLController as SQLController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
    }

    public function getIndex(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'home.twig');
    }

    public function getBDD(Request $request, Response $response, $args){
        try
        {
            $colorCamisetas = [];

            $conn = OpenConnection();
            $tsql = "SELECT * FROM camisetas_mujer2";
            $getShirts= sqlsrv_query($conn, $tsql);
            if (!$getShirts)
                die(sqlsrv_errors());
            $shirtCount = 0;
            while($row = sqlsrv_fetch_array($getShirts, SQLSRV_FETCH_ASSOC))
            {
                array_push($colorCamisetas,$row);
            }
            dd($colorCamisetas);
            sqlsrv_free_stmt($getShirts);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }
    }


}
