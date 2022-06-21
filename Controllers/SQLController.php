<?php

namespace App\Controllers;

class SQLController
{
    public function OpenConnection()
    {
        $serverName = "DESKTOP-7OFLMFB\SQLEXPRESS";
        $connectionOptions = array("Database"=>"Unike",
            "Uid"=>"sa", "PWD"=>"sa");
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if($conn == false){
            print_r(sqlsrv_errors());
        }

        return $conn;
    }


    public function registerUser($username,$password,$admin,$email){

    }


    public function getUser($user){

        try
        {

            $userLine = [
            ];

            $conn = $this->OpenConnection();
            $tsql = "SELECT * FROM USUARIOS WHERE USUARIO = '$user' ";
            $getUser= sqlsrv_query($conn, $tsql);

            if (!$getUser) {
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getUser, SQLSRV_FETCH_ASSOC))
            {
                array_push($userLine,$row);
            }
            sqlsrv_free_stmt($getUser);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            return 'error';
        }

        return $userLine;
    }

    public function getProductsUser($id){
        try
        {

            $userLine = [
            ];

            $conn = $this->OpenConnection();
            $tsql = "
            SELECT COMPRAS.id_producto,COMPRAS.id_usuario,COMPRAS.fecha_compra,COMPRAS.categoria,COMPRAS.cantidad, 
            PRODUCTOS.nombre,PRODUCTOS.url,PRODUCTOS.precio,PRODUCTOS.PRECIO - (PRODUCTOS.PRECIO*(PRODUCTOS.DESCUENTO/100)) as PRECIO_FINAL
            FROM COMPRAS
            INNER JOIN USUARIOS
            ON COMPRAS.id_usuario = USUARIOS.id
            INNER JOIN PRODUCTOS
            ON COMPRAS.id_producto = PRODUCTOS.id
            WHERE COMPRAS.id_usuario = $id
            ORDER BY categoria ASC;
            ";
            $getUser= sqlsrv_query($conn, $tsql);

            if (!$getUser) {
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getUser, SQLSRV_FETCH_ASSOC))
            {
                array_push($userLine,$row);
            }
            sqlsrv_free_stmt($getUser);
            sqlsrv_close($conn);
        }
        catch(Exception $e)
        {
            return 'error';
        }

        return $userLine;
    }

}