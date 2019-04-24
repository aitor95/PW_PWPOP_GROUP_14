<?php
/**
 * Created by PhpStorm.
 * User: AitorBlesa
 * Date: 15/04/2019
 * Time: 21:54
 */

$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/View/templates', [
        'cache' => false, //__DIR__ . '/../var/cache'
    ]);

    $router = $c->get('router');

    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};
