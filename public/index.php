<?php

    //Twig&Slim Configuration

    require_once __DIR__ . '/../vendor/autoload.php';
    $settings = require_once __DIR__ . '/../config/settings.php';
    $app = new \Slim\App($settings);
    $dependencies = require_once __DIR__ . '/../config/dependencies.php';
    require_once __DIR__ . '/../config/routes.php';

    $app->run();

