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
                header("Location: /login");
                exit;

            } else {

                if ($name == '') {
                    //DISPLAY ERROR IMG

                    header("Location: /registre");
                    exit;
                }
                if ($registered >= 3) {
                    //DISPLAY ERROR EN EMAIL

                    header("Location: /registre");
                    exit;
                }
                if ($registered == 4 || $registered == 1) {
                    //DISPLAY ERROR EN USERNAME

                    header("Location: /registre");
                    exit;
                }

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
            header("Location: /");
            exit;

        }else{

            //No acierta usuario o contraseña
            header("Location: /login");
            exit;

        }

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }

}


