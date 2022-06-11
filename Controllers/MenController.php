<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MenController
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
    }

    public function getShirt(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $shirts = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT * FROM CAMISETAS_HOMBRE";
            $getShirts= sqlsrv_query($conn, $tsql);
            if (!$getShirts)
                die(sqlsrv_errors());
            $shirtCount = 0;
            while($row = sqlsrv_fetch_array($getShirts, SQLSRV_FETCH_ASSOC))
            {
                array_push($shirts,$row);
            }
            sqlsrv_free_stmt($getShirts);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }

        $allShirts = [
            'shirtData' => $shirts,
        ];

        return $this->view->render($response, '/men/index.twig',$allShirts);
    }

    public function getUniqueShirt(Request $request, Response $response, $args)
    {
        return $this->view->render($response, '/men/index.twig');
    }

    public function vue(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $shirts = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT * FROM CAMISETAS_HOMBRE";
            $getShirts= sqlsrv_query($conn, $tsql);
            if (!$getShirts)
                die(sqlsrv_errors());
            $shirtCount = 0;
            while($row = sqlsrv_fetch_array($getShirts, SQLSRV_FETCH_ASSOC))
            {
                array_push($shirts,$row);
            }
            sqlsrv_free_stmt($getShirts);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }

        $allShirts = [
            'shirtData' => $shirts,
        ];

        return $this->view->render($response, '/vue.twig',$allShirts);
    }

}