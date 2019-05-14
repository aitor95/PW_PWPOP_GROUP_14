<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwPop\Controller\UserController;


$app->get('/login', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'login.twig', [
       // 'name' => $args['name']
    ]);
});

$app->get('/registre', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'registre.twig', [
       // 'name' => $args['name']
    ]);
});

$app->get('/index', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.twig', [
        // 'name' => $args['name']
    ]);
});

$app->get('/search', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'search.twig', [
        // 'name' => $args['name']
    ]);
});

$app->post('/registration',UserController::class . ':registerAction');

$app->post('/user',UserController::class . ':loginAction');

