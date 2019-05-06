<?php

use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use SallePW\SlimApp\Model\Database\Database;
use Slim\Container;


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
<<<<<<< HEAD

$container['controller'] = new \PwPop\Controller\UserController($container['model']);

$container['flash'] = function () {
    return new Messages();
};

$container['db'] = function (Container $c) {
    return Database::getInstance(
        $c['settings']['db']['username'],
        $c['settings']['db']['password'],
        $c['settings']['db']['host'],
        $c['settings']['db']['dbName']
    );
};

$container['user_repo'] = function (Container $c) {
    return new PDORepository($c->get('db'));
};


return $container;
=======
>>>>>>> parent of 89d7904... Intento de Implementación de Login i Registre
