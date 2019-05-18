<?php

namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\Product;
use PwPop\Model\User;

final class IndexController
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

    public function productsUpdate(Request $request, Response $response): Response
    {
        try {

            $_SESSION['image'] = null;

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $products = $repository->takeProducts();

            if($_SESSION['logged'] == true){

                //Agafem el usuari per gestionar que no mostri els seus productes
                $user = $repository->takeUser($_SESSION['email']);
                $i = 0;
                $j = 0;
                $newArray=[];
                if($_GET['size']==null){
                    $_GET['size']=5;
                }

                while(($i<$_GET['size']) && (sizeof($products) > $j)){
                    //Controlar que el is_active sea igual a 1 y que el soldout sea igual a 0
                    if($products[$j][1] != $user->getUsername() && $products[$j][7] == 1 && $products[$j][8] == 0){
                        array_push($newArray, $products[$j]);
                        $i++;
                    }
                    $j++;
                }

            }else{

                $newArray = array_slice($products, 0, 5, true);

            }

            if($newArray == null){
                $_SESSION['success_message'] = 'No products Found!';
            }else{
                $_SESSION['success_message'] = '';
            }

            $_SESSION['products'] = $newArray;
            return $this->container->get('view')->render($response, 'index.twig', [
                'products' => $_SESSION['products'],
                'size' => $_GET['size'],
                'confirmed' => $_SESSION['confirmed'],
                'success_message' => $_SESSION['success_message'] ?? null,
                'logged' => $_SESSION['logged'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'profileImage' => $_SESSION['profileImage']
            ]);

        } catch
        (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}