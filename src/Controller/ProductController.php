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


    public function loadProductInfo(Request $request, Response $response): Response{

        if($_SESSION['image'] == null){

            $_SESSION['image'] = $_REQUEST['image'];
            header('Location: /product');
            exit;

        }else{

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $products=$repository->takeProducts();

            foreach($products as $product){
                if($product[5] == $_SESSION['image']){
                    $my_product = $product;
                }
            }

            //Find if it's owner or buyer
            $user = $repository->takeUser($_SESSION['email']);
            $owner = false;
            if($my_product[1] == $user->getUsername()){
                $owner = true;
            }


            return $this->container->get('view')->render($response, 'product.twig', [
                'success_message' => $_SESSION['success_message'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'logged' => $_SESSION['logged'] ?? null,
                'title' => $my_product[2],
                'description' => $my_product[3],
                'price' => $my_product[4],
                'productImg' => $my_product[5],
                'category' => $my_product[6],
                'owner' => $owner

            ]);
        }

    }

}