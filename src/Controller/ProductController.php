<?php


namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\Product;
use PwPop\Model\User;


final class ProductController
{

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

    public function uploadAction(Request $request, Response $response): Response{

        try{

            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $user = $repository->takeUser($_SESSION['email']);
            $products=$repository->takeProducts();
            $id = sizeof($products) + 1;

            $name = (new FileController)->uploadProductAction($request,$response,$user->getUsername().$id);

            $product = new Product(
                $id,
                $user->getUsername(),
                $data['title'],
                $data['description'],
                $data['price'],
                $data['category'],
                $name
            );


            if (!($name == '')) {

                //Si es correcta guardamos al usario en la database
                $repository->saveProduct($product);
                $_SESSION['success_message'] = 'Product Uploaded!';

                return $this->container->get('view')->render($response, 'index.twig', [
                    'products' => $_SESSION['products'],
                    'success_message' => $_SESSION['success_message'],
                    'logged' => $_SESSION['logged'],
                ]);

            } else {

                //DISPLAY ERROR IMG
                $errorImg = "Wrong Image Format, Accepted: jpg, png, jpeg";

                return $this->container->get('view')->render($response, 'upload.twig', [
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

}