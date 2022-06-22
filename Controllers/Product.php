<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Product
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
        $this->session = $container->get('session');
    }

    public function getUniqueProduct(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();
        try
        {
            $SQLController = new SQLController();

            $valorations = $this->getValorations($params['id']);

            $uniqueProduct = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT id,nombre,precio,url,descuento,cantidad,categoria,back_url,single_url,precio - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) AS PRECIO_FINAL 
            FROM PRODUCTOS
            WHERE ID = ".$params['id'] .";";
            $getUniqueProduct= sqlsrv_query($conn, $tsql);

            if (!$getUniqueProduct){
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array( $getUniqueProduct, SQLSRV_FETCH_ASSOC))
            {
                array_push($uniqueProduct,$row);
            }
            sqlsrv_free_stmt($getUniqueProduct);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }

        $uniqueProduct = [
            'productData' => $uniqueProduct,
            'valorations' => $valorations,
        ];

        return $this->view->render($response, 'shop/single-product/index.twig',$uniqueProduct);
    }

    public function getValorations($id)
    {
        try
        {
            $SQLController = new SQLController();

            $valorations = [
            ];

            $conn = $SQLController->OpenConnection();
            $tsql = "SELECT VALORACIONES.ID,VALORACIONES.descripcion,VALORACIONES.estrellas,USUARIOS.usuario 
                    FROM VALORACIONES
                    INNER JOIN USUARIOS
                    ON VALORACIONES.ID_USUARIO = USUARIOS.id
                    INNER JOIN PRODUCTOS
                    ON VALORACIONES.ID_PRODUCTO = PRODUCTOS.id
                    WHERE VALORACIONES.ID_PRODUCTO= $id;";
            $getValoration= sqlsrv_query($conn, $tsql);

            if (!$getValoration){
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array( $getValoration, SQLSRV_FETCH_ASSOC))
            {
                array_push($valorations,$row);
            }
            sqlsrv_free_stmt($getValoration);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }

        return $valorations;
    }

    public function registerValoration(Request $request, Response $response)
    {

        $params = $request->getParams();

        $SQLController = new SQLController();

        $user = $SQLController->getUser($this->session->get('user'));
        $user_id = $user[0]['ID'];
        $product_id = $params['id_producto'];
        $valoration = $params['valoration'];

        $url = $params['url_product'];

        if (isset($params['stars'])){
            $stars = count($params['stars']);
        } else {
            $stars = 0;
        }

        try
        {
            $SQLController = new SQLController();


            $conn = $SQLController->OpenConnection();
            $tsql = "
                INSERT INTO VALORACIONES (descripcion ,estrellas, ID_USUARIO,ID_PRODUCTO)
                VALUES ('$valoration',$stars,$user_id,$product_id);";
            $setValoration= sqlsrv_query($conn, $tsql);

            if (!$setValoration){
                die(sqlsrv_errors());
            }

            sqlsrv_free_stmt($setValoration);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            echo("Error!");
        }

        $data = [
            'data' => [
                'status' => 'OK',
                'message' => 'Se ha añadido la valoración correctamente',
                'url' => $url,
            ],
        ];


        return $this->view->render($response, 'home/index.twig',$data);
    }

    public function getBuy(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();

        dd($params);

        return $this->view->render($response, 'shop/single-product/index.twig',$uniqueProduct);
    }

}
