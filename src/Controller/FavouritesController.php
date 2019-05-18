<?php
/**
 * Created by PhpStorm.
 * User: jl
 * Date: 2019-05-18
 * Time: 13:39
 */

namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\User;


class FavouritesController
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

    public function like(Request $request, Response $response): Response{


        if($_SESSION['image'] == null){

            $_SESSION['image'] = $_REQUEST['image'];
            $_SESSION['productInfo'] = $_SESSION['image'];

            //Si esta accediendo de manera manual al producto
            if($_SESSION['productInfo'] == null){

                header('Location: /404');
                exit;

            }else{

                header('Location: /like');
                exit;

            }

        }else{

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $products=$repository->takeProducts();

            foreach($products as $product){
                if($product[5] == $_SESSION['image']){
                    $my_product = $product;
                }
            }

            $repository->addFav($my_product[5],$_SESSION['email']);

            $_SESSION['success_message'] = 'Product Added To Favourites';

            return $this->container->get('view')->render($response, 'index.twig', [
                'success_message' => $_SESSION['success_message'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'products' => $_SESSION['products'],
                'logged' => $_SESSION['logged'] ?? null,
                'confirmed' => $_SESSION['confirmed'],
                'profileImage' => $_SESSION['profileImage']
            ]);
        }
    }

    public function showFavourites(Request $request, Response $response): Response{

        try {

            $_SESSION['image'] = null;

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $products = $repository->takeProducts();

            //Agafem el usuari per gestionar que no mostri els seus productes
            $user = $repository->takeUser($_SESSION['email']);
            $j = 0;
            $newArray=[];

            while(sizeof($products) > $j){
                //Controlem que no sigui un producte meu, que no estigui ni soldout i quee stigui active. FInalment mirem que estigui com a favorit nostre
                if($products[$j][8] == 0 && $products[$j][7] == 1 && $repository->isFav($products[$j][5],$_SESSION['email'])){
                    array_push($newArray, $products[$j]);
                }
                $j++;
            }

            if($newArray == null){
                $_SESSION['success_message'] = 'No products Found!';
            }else{
                $_SESSION['success_message'] = '';
            }

            $_SESSION['my_products'] = $newArray;

            return $this->container->get('view')->render($response, 'favourites.twig', [
                'products' => $_SESSION['my_products'],
                'confirmed' => $_SESSION['confirmed'],
                'success_message' => $_SESSION['success_message'] ?? null,
                'logged' => $_SESSION['logged'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'profileImage' => $_SESSION['profileImage']
            ]);

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

    }

}