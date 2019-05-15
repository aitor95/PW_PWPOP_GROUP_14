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

            // We should validate the information before creating the entity
            $user = new User(
                $data['email'],
                $data['password'],
                $data['birthDate'],
                $data['name'],
                $data['username'],
                $data['phone'],
                new DateTime(),
                new DateTime()
            );

            //Controlamos si la imagen es correcta y que el usuario no este registrado (email o username)
            $registered = $repository->isRegistered($data['email'], $data['username']);
            $name = (new FileController)->uploadAction($request,$response,$data['username']);

            //TODO: Implementar FLASH MESSAGES
            if (!($name == '') && ($registered == 0)) {

                //Si es correcta guardamos al usario en la database
                $repository->save($user,$name);
                //header("refresh: 3");
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

        //Preguntar a la database si hay algun usuario que concorda
        $data = $request->getParsedBody();

        /** @var PDORepository $repository */
        $repository = $this->container->get('user_repo');


        //TODO: Implementar FLASH MESSAGES

        if($repository->login($data['email'], $data['password'])){

            //Login
            $_SESSION['logged'] = true;

            return $this->container->get('view')->render($response, 'index.twig', [
                'success' => 'Login Success!',
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

        return $this->container->get('view')->render($response, 'index.twig', [
            'success' => 'Logged Out, See you Soon!',
            'logged' => $_SESSION['logged'],
        ]);

    }

}


