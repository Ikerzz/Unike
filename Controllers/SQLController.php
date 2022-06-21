<?php

namespace App\Controllers;

//use http\Env\Response;
use Valitron\Validator as Valitron;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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


    public function registerUser($name,$lastname,$username,$password,$email){
        $admin = 0;

        $v = new Valitron(array
        (
        'name' => $name,
        'lastname' => $lastname,
        'username' => $username,
        'password' => $password,
        'email' => $email,
        ));
        $v->rule('required', 'username');
        $v->rule('required', 'name');
        $v->rule('required', 'lastname');
        $v->rule('required', 'password');
        $v->rule('email', 'email');
        if($v->validate()) {
            try
            {
                $passcrypted = (password_hash($password,PASSWORD_BCRYPT));

                $conn = $this->OpenConnection();
                $tsql = "
                INSERT INTO USUARIOS (usuario, [password], administrador,email,nombre,apellidos)
                VALUES ('$username','$passcrypted','$admin','$email','$name','$lastname');"
                ;
                $insertUser= sqlsrv_query($conn, $tsql);

                if (!$insertUser) {
                    die(sqlsrv_errors());
                }
                sqlsrv_free_stmt($insertUser);
                sqlsrv_close($conn);

                return $data = [
                    'data' => [
                        'status' => 'OK',
                        'message' => 'El registro se ha realizado correctamente!'
                    ],
                ];
            }
            catch(Exception $e)
            {
                return $data = [
                    'data' => [
                        'status' => 'KO',
                        'message' => (sqlsrv_errors()),
                    ]
                ];
            }

        } else {
             $data = 'Alguno de los campos es inválido!';

             return $data;
        }
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
            return $data = [
                'data' => [
                    'error' => (sqlsrv_errors()),
                ],
            ];
        }

        return $userLine;
    }


    public function getAllUser(){
        try
        {
            $users = [
            ];

            $conn = $this->OpenConnection();
            $tsql = "SELECT * FROM USUARIOS";
            $getUsers= sqlsrv_query($conn, $tsql);

            if (!$getUsers) {
                die(sqlsrv_errors());
            }

            while($row = sqlsrv_fetch_array($getUsers, SQLSRV_FETCH_ASSOC))
            {
                array_push($users,$row);
            }
            sqlsrv_free_stmt($getUsers);
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

        return $users;
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
            return $data = [
                'data' => [
                    'error' => (sqlsrv_errors()),
                ],
            ];
        }

        return $userLine;
    }

    public function deleteEditedUser($id){
            try
            {
                $conn = $this->OpenConnection();
                $tsql = "DELETE FROM USUARIOS WHERE id = '$id';"
                ;
                $insertUser= sqlsrv_query($conn, $tsql);

                if (!$insertUser) {
                    die(sqlsrv_errors());
                }
                sqlsrv_free_stmt($insertUser);
                sqlsrv_close($conn);

                return true;
            }
            catch(Exception $e)
            {
                return false;
            }

    }

    public function insertEditedUser($email,$name,$lastname,$username,$password,$admin){

        $v = new Valitron(array
        (
            'name' => $name,
            'lastname' => $lastname,
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'admin' => $admin,
        ));
        $v->rule('email', 'email');
        $v->rule('required', 'username');
        $v->rule('required', 'name');
        $v->rule('required', 'lastname');
        $v->rule('required', 'password');
        $v->rule('required', 'admin');
        if($v->validate()) {
            try
            {
                $passcrypted = (password_hash($password,PASSWORD_BCRYPT));

                $conn = $this->OpenConnection();
                $tsql = "
                INSERT INTO USUARIOS (usuario, [password], administrador,email,nombre,apellidos)
                VALUES ('$username','$passcrypted','$admin','$email','$name','$lastname');"
                ;
                $insertUser= sqlsrv_query($conn, $tsql);

                if (!$insertUser) {
                    die(sqlsrv_errors());
                }
                sqlsrv_free_stmt($insertUser);
                sqlsrv_close($conn);

                return $data = [
                    'status' => 'OK',
                    'message' => 'Se ha editado el usuario correctamente!'
                ];
            }
            catch(Exception $e)
            {
                return $data = [
                    'data' => [
                        'status' => 'KO',
                        'message' => (sqlsrv_errors()),
                    ]
                ];
            }

        } else {
            $data = 'Alguno de los campos es inválido!, la edición ha sido inválida.';

            return $data;
        }
    }
}