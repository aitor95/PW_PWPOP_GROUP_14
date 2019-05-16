<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwPop\Controller\UserController;
use PwPop\Controller\Middleware\SessionMiddleware;
use PwPop\Controller\ProfileController;
use PwPop\Controller\ProductController;


$app->get('/login', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'login.twig', [
       'success_message' => $_SESSION['success_message'] ?? null,
        'logged' => $_SESSION['logged'] ?? null,
    ]);
});

$app->get('/registre', function (Request $request, Response $response, array $args) {

    return $this->view->render($response, 'registre.twig', [
        'logged' => $_SESSION['logged'] ?? null,
    ]);
});

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.twig', [
        'logged' => $_SESSION['logged'] ?? null,
    ]);
});

$app->get('/search', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'search.twig', [
        'logged' => $_SESSION['logged'] ?? null,
    ]);
});

$app->get('/profile', ProfileController::class.':profileUpdate');

$app->get('/403', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, '403.twig', [
        'logged' => $_SESSION['logged'] ?? null,
    ]);
});

$app->get('/upload', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'upload.twig', [
        'logged' => $_SESSION['logged'] ?? null
    ]);
});

$app->get('/product', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'product.twig', [
        'logged' => $_SESSION['logged'] ?? null
    ]);
});

$app->get('/logout', UserController::class.':logOut');

$app->post('/registration',UserController::class . ':registerAction');

$app->post('/modify',ProfileController::class . ':modifyAction');

$app->post('/user',UserController::class . ':loginAction');

$app->post('/uploadProduct', ProductController::class . ':uploadAction');

$app->add(SessionMiddleware::class);

$_SESSION['logged'] = false;




