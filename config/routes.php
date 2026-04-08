<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use \ApiPlatform\Metadata\HttpOperation;

return function (RoutingConfigurator $routes): void {
//    $routes
//        ->add('app_index', '/')
//        ->controller([IndexController::class, 'index'])
//        ->defaults(['breadcrumb' => [
//            'template' => 'Home page',
//            'title' => 'Home',
//            'parent' => null,
//        ]]);
    $routes
        ->add('auth', '/v1/auth')
        ->methods([HttpOperation::METHOD_POST])
    ;
};
