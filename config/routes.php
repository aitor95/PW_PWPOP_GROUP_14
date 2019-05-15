<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwPop\Controller\UserController;
use PwPop\Controller\Middleware\SessionMiddleware;
use PwPop\Controller\ProfileController;


$app->get('/login', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'login.twig', [
       // 'name' => $args['name']
    ]);
});

$app->get('/registre', function (Request $request, Response $response, array $args) {

    return $this->view->render($response, 'registre.twig', [
        'logged' => $_SESSION['logged'],
    ]);
});

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.twig', [
        'logged' => $_SESSION['logged'],
    ]);
});

$app->get('/search', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'search.twig', [
        'logged' => $_SESSION['logged'],
    ]);
});

//$app->get('/profile', ProfileController::class.':profileUpdate');

$app->get('/403', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, '403.twig', [
        'logged' => $_SESSION['logged'],
    ]);
});

$app->get('/logout', UserController::class.':logOut');

$app->post('/registration',UserController::class . ':registerAction');

$app->post('/user',UserController::class . ':loginAction');

$app->add(SessionMiddleware::class);

$_SESSION['logged'] = false;




