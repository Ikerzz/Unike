<?php

namespace App\Controllers;

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

        try
        {
            $SQLController = new SQLController();

            $productLine = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT TOP 4 id,nombre,precio,url,descuento,categoria,precio - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) as PRECIO_FINAL from PRODUCTOS ORDER BY newid();";
            $getRandomProducts= sqlsrv_query($conn, $tsql);

            if (!$getRandomProducts) {
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getRandomProducts, SQLSRV_FETCH_ASSOC))
            {
                array_push($productLine,$row);
            }
            sqlsrv_free_stmt($getRandomProducts);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            return $data = [
                'data' => [
                    'error' => (sqlsrv_errors()),
                ],
            ];
        }

        $data = [
            'products' => $productLine,
        ];

        return $this->view->render($response, '/home/index.twig',$data);
    }

    public function getSubscribed(Request $request, Response $response){
        $data = [
            'data' => [
                'status' => 'OK',
                'message' => 'Gracias por subscribirte, estate atento a nuestro boletín!',
            ],
        ];
        return $this->view->render($response, '/home/index.twig',$data);
    }



}
