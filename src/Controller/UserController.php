<?php

namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\User;


final class UserController
{

    /** @var ContainerInterface */
    private $container;
    private const COOKIES_ADVICE = 'cookies_advice';

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
            $name = (new FileController)->uploadAction($request, $response, $data['username']);

            $user = new User(
                $data['email'],
                $data['password'],
                $data['birthDate'],
                $data['name'],
                $data['username'],
                $data['phone'],
                new DateTime(),
                new DateTime(),
                $name,
                1,
                0
            );

            if (!($name == '') && ($registered == 0)) {

                //Si es correcta guardamos al usario en la database
                $repository->save($user, $name);
                //Enviamos el email de confirmación
                (new MailerController())->confirmationMail($user);

                return $this->container->get('view')->render($response, 'login.twig', [
                    'hide_menu' => 'hide',
                    'confirmed' => $_SESSION['confirmed'] ?? null,
                    'success' => 'Registration SUccess!' ?? null,
                    'logged' => $_SESSION['logged'] ?? false,
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
                    'errorEmail' => $errorEmail ?? null,
                    'hide_menu' => 'hide',
                    'confirmed' => $_SESSION['confirmed'] ?? null,
                    'errorUsername' => $errorUsername ?? null,
                    'errorImg' => $errorImg ?? null,
                    'logged' => $_SESSION['logged'] ?? false,
                ]);

            }

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }

    public function loginAction(Request $request, Response $response): Response
    {
        try {
            //$adviceCookie = FigRequestCookies::get($request, self::COOKIES_ADVICE);
           // $isWarned = $adviceCookie->getValue();

            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            if ($repository->login($data['email'], $data['password'])) {
                if ($data['remember'] == 'on'){
                    setcookie("username", $data['email'], time()+1314000);
                }
                $_SESSION['success_message'] = 'Login Success!';
                $_SESSION['logged'] = true;

                //Mirem si ens han introduit el login o el email
                if($_SESSION['loginMethod'] == 'email'){
                    $user=$repository->takeUser($data['email']);
                    $_SESSION['email'] = $data['email'];
                }else{
                    $user=$repository->takeEmail($data['email']);
                    $_SESSION['email'] = $user->getEmail();
                }

                $_SESSION['confirmed'] = $user->getConfirmed();
                $_SESSION['profileImage'] = $user->getProfileImg();

                return $this->container->get('view')->render($response, 'index.twig', [
                    'confirmed' => $_SESSION['confirmed'] ?? null,
                    'products' => $_SESSION['products'] ?? null,
                    'success_message' => 'Login Success!' ?? null,
                    'logged' => $_SESSION['logged'] ?? false,
                    'email' => $_SESSION['email'] ?? null,
                    'profileImage' => $_SESSION['profileImage'] ?? null
                ]);

            } else {

                //No acierta usuario o contraseña
                return $this->container->get('view')->render($response, 'login.twig', [
                    'confirmed' => $_SESSION['confirmed'] ?? null,
                    'error' => $_SESSION['error'] ?? null,
                    'logged' => $_SESSION['logged'] ?? false,
                    'hide_menu' => 'hide',
                ]);

            }

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }

    public function logOut(Request $request, Response $response): Response
    {

        $_SESSION['logged'] = false;
        session_destroy();
        $_SESSION['success_message'] = 'Logged Out, See you Soon!';

        return $this->container->get('view')->render($response, 'index.twig', [
            'confirmed' => $_SESSION['confirmed'] ?? null,
            'products' => $_SESSION['products'] ?? null,
            'success_message' => $_SESSION['success_message'] ?? null,
            'logged' => $_SESSION['logged'] ?? false,
            'profileImage' => $_SESSION['profileImage'] ?? null,
        ]);

    }

    public function deleteAccount(Request $request, Response $response): Response
    {

        try {

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $useraux = $repository->takeUser($_SESSION['email']);

            $useraux->setIsActive(0);

            $repository->update($useraux, 2);
            $repository->deleteProducts($useraux->getUsername());

            $this->logOut($request, $response);

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }

    public function confirmAccount(Request $request, Response $response): Response
    {

        try {

            $username = $_REQUEST['username'];

            $repository = $this->container->get('user_repo');

            $repository->confirmAccount($username);

            $this->logOut($request, $response);

        } catch (\Exception $e) {

            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }

    public function resendConfirmation(Request $request, Response $response): Response
    {

        try {

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');
            $user = $repository->takeUser($_SESSION['email']);

            (new MailerController())->confirmationMail($user);

            $_SESSION['success_message'] = 'Confirmation Email Sended!';

            return $this->container->get('view')->render($response, 'index.twig', [
                'confirmed' => $_SESSION['confirmed'] ?? null,
                'products' => $_SESSION['products'] ?? null,
                'success_message' => $_SESSION['success_message'] ?? null,
                'logged' => $_SESSION['logged'] ?? false,
                'profileImage' => $_SESSION['profileImage'] ?? null,
            ]);


        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }
}


