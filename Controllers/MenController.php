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

    public function getShirts(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $shirts = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT *, precio - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) as PRECIO_FINAL 
                    FROM PRODUCTOS 
                    WHERE categoria = 'camiseta_hombre';";
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

    public function getPants(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $pants = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT *, precio - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) as PRECIO_FINAL 
                    FROM PRODUCTOS 
                    WHERE categoria = 'pantalon_hombre';";
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


    public function getFootWear(Request $request, Response $response, $args)
    {
        try
        {
            $SQLController = new SQLController();

            $footWear = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT *, precio - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) as PRECIO_FINAL 
                    FROM PRODUCTOS 
                    WHERE categoria = 'zapatilla_hombre';";
            $getFootWear = sqlsrv_query($conn, $tsql);

            if (!$getFootWear) {
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

}