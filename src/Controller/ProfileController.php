<?php

namespace PwPop\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;



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
                'logged' => $_SESSION['logged'],
                'email' => $email,
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'birthDate' => $user->getBirthDate(),
                'phone' => $user->getPhone(),
                'profileImg' => $user->getProfileImg(),
            ]);

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }
}