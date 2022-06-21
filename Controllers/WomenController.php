<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WomenController
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
    }

    public function getShirts(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $shirts = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT * FROM PRODUCTOS WHERE categoria = 'camiseta_mujer';";
            $getShirts= sqlsrv_query($conn, $tsql);

            if (!$getShirts){
                die(sqlsrv_errors());
            }

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
            'productData' => $shirts,
        ];


        return $this->view->render($response, 'shop/index.twig',$allShirts);
    }

    public function getUniqueShirt(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();

        return $this->view->render($response, 'shop/single-product/index.twig');
    }

    public function getPants(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $pants = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT * FROM PRODUCTOS WHERE categoria = 'pantalon_mujer';";
            $getPants= sqlsrv_query($conn, $tsql);

            if (!$getPants){
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getPants, SQLSRV_FETCH_ASSOC))
            {
                array_push($pants,$row);
            }
            sqlsrv_free_stmt($getPants);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }


        $allPants = [
            'productData' => $pants,
        ];

        return $this->view->render($response, 'shop/index.twig',$allPants);
    }

    public function getUniquePant(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();

        return $this->view->render($response, 'shop/single-product/index.twig');
    }

    public function getFootWear(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $footWear = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT * FROM PRODUCTOS WHERE categoria = 'zapatilla_mujer';";
            $getFootWear= sqlsrv_query($conn, $tsql);

            if (!$getFootWear){
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getFootWear, SQLSRV_FETCH_ASSOC))
            {
                array_push($footWear,$row);
            }
            sqlsrv_free_stmt($getFootWear);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }


        $allFootWear = [
            'productData' => $footWear,
        ];

        return $this->view->render($response, 'shop/index.twig',$allFootWear);
    }

    public function getUniqueFootWear(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();

        return $this->view->render($response, 'shop/single-product/index.twig');
    }

}