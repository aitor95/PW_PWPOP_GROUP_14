<?php

namespace PwPop\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\User;
use DateTime;


final class ProfileController{

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

    public function profileUpdate(Request $request, Response $response): Response
    {

        try{

            $email = $_SESSION['email'];

            /** @var PDORepository $repository **/
            $repository = $this->container->get('user_repo');
            $user = $repository->takeUser($email);

            return $this->container->get('view')->render($response, 'profile.twig', [
                'confirmed' => $_SESSION['confirmed'],
                'logged' => $_SESSION['logged'],
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'birthDate' => $user->getBirthDate(),
                'phone' => $user->getPhone(),
                'profileImage' => $_SESSION['profileImage'],
                'error_message' => $_SESSION['success_message'],
            ]);

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }

    public function modifyAction(Request $request, Response $response): Response{

        try{

            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            //Agafem el usuari de la sessio en actiu
            $useraux = $repository->takeUser($_SESSION['email']);

            //Agafem la persona del email que he cambiat o no
            $useraux2 = $repository->takeUser($data['email']);

            //Volem saber si esta registrat, per poder utilitzar el correu o no
            $registered = $repository->isRegistered($data['email'], $useraux->getUsername());
            //Guardem també la nova imatge
            $name = (new FileController)->uploadAction($request,$response,$useraux->getUsername());

            if($name==''){
                $name = $useraux->getProfileImg();
            }

            $user = new User(
                $data['email'],
                $data['password'],
                $data['birthDate'],
                $data['name'],
                $useraux->getUsername(),
                $data['phone'],
                new DateTime(),
                new DateTime(),
                $name,
                1,
                $useraux->getConfirmed()
            );

            //Controlem si el email esta ja agafat o no (potser que sigui jo el que tinc el email, llavors deixem actualitzar)
            if (($registered == 4) && ($useraux2->getUsername() == $useraux->getUsername())) {

                $repository->update($user,0);
                $_SESSION['email'] = $data['email'];
                $_SESSION['success_message'] = 'Data Actualized!';


                return $this->container->get('view')->render($response, 'index.twig', [
                    'success_message' => $_SESSION['success_message'],
                    'confirmed' => $_SESSION['confirmed'],
                    'logged' => $_SESSION['logged'],
                    'profileImg' => $user->getProfileImg(),
                ]);


            }else{
                //Cas en el que email estigui agafat
                $_SESSION['success_message'] = 'Email in Use!';

                return $this->profileUpdate($request,$response);
            }


        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }
}