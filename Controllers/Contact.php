<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator as Valitron;

class Contact
{
    protected $view;

    public function __construct($container) {
        $this->view = $container->get('view');
    }

    public function getIndex(Request $request, Response $response, $args)
    {
        return $this->view->render($response, '/contact/index.twig');
    }

    public function registerContactLine(Request $request, Response $response, $args){


        $SQLController = new SQLController();

        $params = $request->getParams();

        $name = $params['name'];
        $lastname = $params['lastname'];
        $description = $params['description'];
        $email = $params['email'];
        $active = 1;
        $description = $SQLController->eliminar_tildes($description);
        $name = $SQLController->eliminar_tildes($name);
        $lastname = $SQLController->eliminar_tildes($lastname);

        $v = new Valitron(array
        (
            'name' => $name,
            'lastname' => $lastname,
            'description' => $description,
            'email' => $email
        ));
        $v->rule('required', 'name');
        $v->rule('required', 'lastname');
        $v->rule('required', 'description');
        $v->rule('email', 'email');
        if($v->validate()) {
            try {

                $conn = $SQLController->OpenConnection();
                $tsql = "INSERT INTO CONTACTO (email,nombre, apellidos,descripcion,activo)
                    VALUES ('$email','$name','$lastname','$description','$active');";
                $insertContactLine = sqlsrv_query($conn, $tsql);

                if (!$insertContactLine) {
                    die(sqlsrv_errors());
                }

                sqlsrv_free_stmt($insertContactLine);
                sqlsrv_close($conn);
            } catch (Exception $e) {
                return $data = [
                    'data' => [
                        'error' => (sqlsrv_errors()),
                    ],
                ];
            }
            $data = [
                'data' => [
                    'status' => 'OK',
                    'message' => 'Se ha enviado correctamente tu petición!',
                ],
            ];

            return $this->view->render($response, '/contact/index.twig',$data);
        } else {
            $data = [
                'data' => [
                    'status' => 'KO',
                    'message' => 'Alguno de los campos introducidos es inválido!',
                ],
            ];

            return $this->view->render($response, '/contact/index.twig',$data);

        }

    }

}
