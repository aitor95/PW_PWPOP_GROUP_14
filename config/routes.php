<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwPop\Controller\UserController;
use PwPop\Controller\Middleware\SessionMiddleware;
use PwPop\Controller\ProfileController;
use PwPop\Controller\ProductController;
use PwPop\Controller\IndexController;
use PwPop\Controller\MyProductsController;
use PwPop\Controller\SearchController;
use PwPop\Controller\FavouritesController;


$app->get('/login', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'login.twig', [
        'confirmed' => $_SESSION['confirmed'] ?? null,
        'success_message' => $_SESSION['success_message'] ?? null,
        'logged' => $_SESSION['logged'] ?? false,
        'hide_menu' => 'hide'
    ]);
});

$app->get('/registre', function (Request $request, Response $response, array $args) {

    return $this->view->render($response, 'registre.twig', [
        'confirmed' => $_SESSION['confirmed'] ?? null,
        'logged' => $_SESSION['logged'] ?? false,
        'hide_menu' => 'hide'
    ]);
});


$app->get('/403', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, '403.twig', [
        'confirmed' => $_SESSION['confirmed'] ?? null,
        'logged' => $_SESSION['logged'] ?? false,
        'hide_menu' => 'hide'
    ]);
});

$app->get('/upload', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'upload.twig', [
        'email' => $_SESSION['email'] ?? null,
        'logged' => $_SESSION['logged'] ?? false,
        'confirmed' => $_SESSION['confirmed'] ?? null,
        'profileImage' => $_SESSION['profileImage'] ?? null,
    ]);
});


$app->get('/profile', ProfileController::class.':profileUpdate');

$app->get('/product', ProductController::class.':loadProductInfo');

$app->get('/like', FavouritesController::class.':like');

$app->get('/myproducts', MyProductsController::class.':productsUpdate');

$app->get('/logout', UserController::class.':logOut');

$app->get('/favourites', FavouritesController::class.':showFavourites');

$app->post('/search', SearchController::class.':productsUpdate');

$app->post('/registration',UserController::class . ':registerAction');

$app->post('/modify',ProfileController::class . ':modifyAction');

$app->post('/user',UserController::class . ':loginAction');

$app->post('/uploadProduct', ProductController::class . ':saveProductAction');

$app->post('/updateProduct', ProductController::class . ':updateAction');

$app->get('/buy', ProductController::class . ':buyProduct');

$app->get('/', IndexController::class.':productsUpdate');

$app->get('/deleteAcc', UserController::class.':deleteAccount');

$app->get('/resend', UserController::class.':resendConfirmation');

$app->get('/confirmation', UserController::class.':confirmAccount');

$app->get('/deleteProd', ProductController::class.':deleteProduct');

$app->add(SessionMiddleware::class);

$_SESSION['logged'] = false;




