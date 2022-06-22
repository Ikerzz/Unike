<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PanelController
{

    public function __construct($container)
    {
        $this->view = $container->get('view');
        $this->session = $container->get('session');
    }

    public function getIndex(Request $request, Response $response)
    {
        if ($this->session->get('user')) {
            return $this->view->render($response, 'login/user/index.twig');;
        } else {
            return $this->view->render($response, 'login/index.twig');;
        }
    }

    public function getRegister(Request $request, Response $response)
    {
        return $this->view->render($response, 'login/register/index.twig');
    }

    public function registerUser(Request $request, Response $response)
    {
        // Recogemos los parametros del formulario

        $params = $request->getParams();

        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Llamamos a la función para registrar pasandole los parametros regidos por post,

        if ($SQLController->getUser($params['username'])) {

            $data = [
                'data' => [
                    'status' => 'KO',
                    'message' => 'El usuario ya existe!',
                ],
            ];


            return $this->view->render($response, 'login/register/index.twig', $data);

        } else {
            $insertUser = $SQLController->registerUser($params['name'], $params['lastname'], $params['username'], $params['password'], $params['email']);

            $data = [
                'data' => [
                    'status' => 'KO',
                    'message' => $insertUser,
                ],
            ];

            return $this->view->render($response, 'login/register/index.twig', $data);
        }
    }

    public function getUser(Request $request, Response $response)
    {

        // Recogemos los parametros del formulario

        $params = $request->getParams();

        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea

        $user = $SQLController->getUser($params['username']);

        //  Verifica si el usuario existe y si no envia un array con un status y un mensaje de error.

        if (!$user) {
            $data = [
                'data' => [
                    'status' => 'KO',
                    'message' => 'El usuario no existe',
                ],

            ];

            return $this->view->render($response, 'login/index.twig', $data);
        }

        //  Verifica si la contraseña conicide y si no, devuelve que no es correcta.

        if (password_verify($params['password'], $user[0]['password'])) {

            // Si es administrador, me devolverá la vista del administrador de lo contrario la del usuario.

            if ($user[0]['administrador'] == 1) {

                //  Setteamos la sesión de user de administrador

                $session = $this->session->set('user', $params['username']);

                // Enviamos la los usuarios para la edición.

                $users = $SQLController->getAllUser();

                $data = [
                    'users' => $users,
                ];

                return $this->view->render($response, 'login/admin/index.twig', $data);

            } else {

                //  Setteamos la sesión de usuario

                $session = $this->session->set('user', $params['username']);

                // Enviamos al controlador el nombre del usuario para que nos devuelva su linea

                $userOn = $SQLController->getUser($this->session->get('user'));

                // Recogemos las compras que tengan el ID de cliente

                $products = $SQLController->getProductsUser($userOn[0]['ID']);

                $allProducts = [
                    'products' => $products,
                    'name' => $user[0]['nombre'],
                ];
                return $this->view->render($response, 'login/user/index.twig', $allProducts);
            }

        } else {
            $data = [
                'data' => [
                    'status' => 'KO',
                    'message' => 'La contraseña es incorrecta',
                ],
            ];
            return $this->view->render($response, 'login/index.twig', $data);
        };

    }

    public function getPanel(Request $request, Response $response)
    {

        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea para saber si es un usuario normal o administrador

        $user = $SQLController->getUser($this->session->get('user'));

        if ($this->session->get('user')) {

            if ($user[0]['administrador'] == 1) {

                // Recogemos todos los usuarios para mostrarlos en el menu admin y luego saber cuales son para editarlos

                $users = $SQLController->getAllUser();

                // Recogemos todas las lineas de contacto para mostrarlas en el menu admin

                $contactLines = $SQLController->getActiveContactLines();

                // Lo guardamos en el Array y se lo pasamos a la vista.

                $data = [
                    'users' => $users,
                    'contactLines' => $contactLines
                ];

                return $this->view->render($response, 'login/admin/index.twig', $data);

            } else {

                // Recogemos las compras que tengan el ID de cliente...

                $products = $SQLController->getProductsUser($user[0]['ID']);


                // Se las metemos al array junto al nombre de usuario para mostrarlo en la página...
                $allProducts = [
                    'products' => $products,
                    'name' => $user[0]['nombre'],
                ];

                return $this->view->render($response, 'login/user/index.twig', $allProducts);
            }


            // Recogemos las compras que tengan el ID de cliente

            $products = $SQLController->getProductsUser($user[0]['ID']);

            $allProducts = [
                'products' => $products,
                'name' => $user[0]['nombre'],
            ];

            return $this->view->render($response, 'login/user/index.twig', $allProducts);

        } else {
            return $this->view->render($response, 'login/index.twig');;
        }
    }


    public function editUser(Request $request, Response $response)
    {

        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea para saber si es un usuario normal o administrador

        $user = $SQLController->getUser($this->session->get('user'));

        // Compruebo si el usuario es administrador

        if ($this->session->get('user')) {

            if ($user[0]['administrador'] == 1) {

                // Recojo los parametros que vienen de post

                $params = $request->getParams();

                // Los envio a la funcion que me trae los datos de la BD

                $userSelected = $SQLController->getUser($params['name']);

                $data = [
                    'userSelected' => $userSelected,
                ];

                // Los devuelvo con la vista

                return $this->view->render($response, 'login/admin/editUser/index.twig', $data);

            } else {
                return $this->view->render($response, 'home/index.twig');
            }
        }
    }

    public function editUserBD(Request $request, Response $response)
    {
        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea para saber si es un usuario normal o

        $user = $SQLController->getUser($this->session->get('user'));

        if ($this->session->get('user')) {

            if ($user[0]['administrador'] == 1) {

                // Recojo los parametros del formulario de editar usuario

                $params = $request->getParams();

                // Borro el usuario

                $deleteUser = $SQLController->deleteEditedUser($params['id']);

                // Lo vuelvo a crear con los nuevos datos.

                $insertUser = $SQLController->insertEditedUser($params['email'], $params['name'], $params['lastname'], $params['user'], $params['password'], $params['admin']);

                // Dentro viene el mensaje de si se ha registrado o dado error y lo devuelvo para imprimirlo.

                $users = $SQLController->getAllUser();

                $data = [
                    'data' => $insertUser,
                    'users' => $users,
                ];

                    return $this->view->render($response, 'login/admin/index.twig', $data);

            } else {
                return $this->view->render($response, 'home/index.twig');
            }
        };
    }

    public function updateContactLine(Request $request, Response $response)
    {
        // Instanciamos la clase del controlador de SQL

        $SQLController = new SQLController();

        // Enviamos al controlador el nombre del usuario para que nos devuelva su linea para saber si es un usuario normal o administrador

        $user = $SQLController->getUser($this->session->get('user'));

        if ($this->session->get('user')) {

            if ($user[0]['administrador'] == 1) {

                // Recojo los parametros del formulario de editar usuario

                $params = $request->getParams();

                // Llamo a la función que actualizará el activo de la linea de contacto pasandole la ID de la linea

                $updateContactLine = $SQLController->updateContactLine($params['id']);

                // Dentro volvera el mensaje de error o de OK

                $data = [
                    'data' => $updateContactLine,
                ];

                return $this->view->render($response, 'login/admin/index.twig', $data);

            } else {
                return $this->view->render($response, 'home/index.twig');
            }
        };
    }

    // Botón LOG-OUT.

    public function killUser(Request $request, Response $response)
    {
        // Mato todas las sesiones y redirecciono al login.
        $this->session->killAll();
        return $response->withRedirect('/login');
    }

}