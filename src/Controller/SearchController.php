<?php

namespace PwPop\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PwPop\Model\Database\PDORepository;
use PwPop\Model\Product;
use PwPop\Model\User;


final class SearchController
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

            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $products = $repository->takeProducts();

            //Agafem el usuari per gestionar que no mostri els seus productes
            $user = $repository->takeUser($_SESSION['email']);
            $i = 0;
            $j = 0;
            $newArray=[];

            if($data['image_title']==null){
                $data['image_title'] = true;
            }
            if($data['min_price']==null){
                $data['min_price'] = true;
            }
            if($data['max_price']==null){
                $data['max_price'] = true;
            }
            if($data['category2']==null){
                $data['category2'] = true;
            }

            while(($i<5) && (sizeof($products) > $j)){
                //Controlar que el is_active sea igual a 1 y que el soldout sea igual a 0
                if($products[$j][1] != $user->getUsername() && $products[$j][7] == 1 && $products[$j][8] == 0 &&
                    $products[$j][2] == $data['image_title'] && $products[$j][6] == $data['category2'] &&
                    $data['min_price'] <= $products[$j][4] && $products[$j][4] <= $data['max_price']){

                    array_push($newArray, $products[$j]);
                    $i++;
                }
                $j++;
            }


            if($newArray == null){
                $_SESSION['success_message'] = 'No products Found!';
            }else{
                $_SESSION['success_message'] = '';
            }
            $_SESSION['search'] = $newArray;
            return $this->container->get('view')->render($response, 'search.twig', [
                'products' => $_SESSION['search'],
                'confirmed' => $_SESSION['confirmed'],
                'success_message' => $_SESSION['success_message'],
                'logged' => $_SESSION['logged'] ?? null,
                'email' => $_SESSION['email'] ?? null,
            ]);

        } catch
        (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }

}