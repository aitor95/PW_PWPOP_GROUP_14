<?php

namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\User;


final class UserController{

    /** @var ContainerInterface */
    private $container;

    /**
     * HelloController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {

            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            //Controlamos si la imagen es correcta y que el usuario no este registrado (email o username)
            $registered = $repository->isRegistered($data['email'], $data['username']);
            $name = (new FileController)->uploadAction($request,$response,$data['username']);

            $user = new User(
                $data['email'],
                $data['password'],
                $data['birthDate'],
                $data['name'],
                $data['username'],
                $data['phone'],
                new DateTime(),
                new DateTime(),
                $name
            );

            //TODO: Implementar FLASH MESSAGES
            if (!($name == '') && ($registered == 0)) {

                //Si es correcta guardamos al usario en la database
                $repository->save($user,$name);

                return $this->container->get('view')->render($response, 'login.twig', [
                    'success' => 'Registrat correctament!',
                    'logged' => $_SESSION['logged'],
                ]);

            } else {

                $errorUsername = "";
                $errorImg = "";
                $errorEmail = "";

                if ($name == '') {
                    //DISPLAY ERROR IMG
                    $errorImg = "Wrong Image Format, Accepted: jpg, png, jpeg";
                }
                if ($registered >= 3) {
                    //DISPLAY ERROR EN EMAIL
                    $errorEmail = "Email Already in use";
                }
                if ($registered == 4 || $registered == 1) {
                    //DISPLAY ERROR EN USERNAME
                    $errorUsername = "Username Already in use";
                }

                return $this->container->get('view')->render($response, 'registre.twig', [
                    'errorEmail' => $errorEmail,
                    'errorUsername' => $errorUsername,
                    'errorImg' => $errorImg,
                    'logged' => $_SESSION['logged'],
                ]);

            }

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);
    }

    public function loginAction(Request $request, Response $response): Response
    {
        try{

        $data = $request->getParsedBody();

        /** @var PDORepository $repository */
        $repository = $this->container->get('user_repo');


        if($repository->login($data['email'], $data['password'])){

            //Login
            $_SESSION['success_message'] = 'Login Success!';
            $_SESSION['email'] = $data['email'];
            $_SESSION['logged'] = true;

            return $this->container->get('view')->render($response, 'index.twig', [
                'success_message' => 'Login Success!',
                'logged' => $_SESSION['logged'],
            ]);

        }else{

            //No acierta usuario o contraseña
            return $this->container->get('view')->render($response, 'login.twig', [
                'error' => 'Username or Email invalid!',
                'logged' => $_SESSION['logged'],
            ]);

        }

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }

    public function logOut (Request $request, Response $response): Response
    {

        $_SESSION['logged'] = false;
        session_destroy();
        $_SESSION['success_message'] = 'Logged Out, See you Soon!';

        return $this->container->get('view')->render($response, 'index.twig', [
            'success_message' => $_SESSION['success_message'],
            'logged' => $_SESSION['logged'],
        ]);

    }

}


