<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PanelController
{

    public function __construct($container) {
        $this->view = $container->get('view');
        $this->session = $container->get('session');
    }

    public function getIndex(Request $request, Response $response)
    {
        return $this->view->render($response, 'login/index.twig');
    }


    public function getRegister(Request $request, Response $response)
    {
        return $this->view->render($response, 'login/register/index.twig');
    }
    public function registerUser(Request $request, Response $response)
    {
        $params = $request->getParams();

        dd($params);
        return $this->view->render($response, 'login/register/index.twig');
    }
    public function getUser(Request $request, Response $response)
    {

        // Recogemos los parametros del formulario

        $params = $request->getParams();

        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea

        $user = $SQLController->getUser($params['username']);

        //  Verifica si el usuario existe
        if(!$user){
            $data = [
                'data' =>[
                    'status' => 'KO',
                    'error' => 'El usuario no existe',
                ],

            ];
            return $this->view->render($response, 'login/index.twig',$data);
        }

        //  Verifica si la contraseña conicide y si no, devuelve que no es correcta.

        if (password_verify($params['password'],$user[0]['password'])) {
            if ($user[0]['administrador'] == 1 ){
                $session = $this->session->set('user', $params['username']);
                return $this->view->render($response, 'login/admin/index.twig');
            } else {
                $session = $this->session->set('user', $params['username']);
                return $this->view->render($response, 'login/user/index.twig');
            }

        } else {
            $data = [
                'data' => [
                    'status' => 'KO',
                    'error' => 'La contraseña es incorrecta',
                    ],
            ];
            return $this->view->render($response, 'login/index.twig',$data);
        };

    }

    public function getPanel(Request $request, Response $response)
    {

        return $this->view->render($response, 'login/user/index.twig');
    }

    public function getPanelProducts(Request $request, Response $response)
    {
        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea

        $user = $SQLController->getUser($this->session->get('user'));

        $products = $SQLController->getProductsUser($user[0]['ID']);

        $allProducts = [
            'products' => $products,
        ];


        return $this->view->render($response, 'login/user/products/index.twig',$allProducts);

    }

    public function killSesion(Request $request, Response $response)
    {
        $this->session->killAll();
    }

}