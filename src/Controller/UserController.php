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

            $repository->save($user);
            //Guardamos la imagen también
            //$response = (new FileController)->uploadAction($request,$response);

            //FALTA IMPLEMENTAR (Como poner mensaje de succsess en el login?)----------------
            header("Location: /login");
            exit;
            //PENE
            //----------------------------------------------------------------------------

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

        if($repository->login($data['email'], $data['password'])){

            //Login
            header("Location: /index");
            exit;

        }else{

            //Try Again (No se porque no muestra el alert (por el exit, pero es necesario para cambiar de pagina))
            $alert = "Not Registered, Try Again";
            echo "<script type='text/javascript'>alert('$alert');</script>";
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
