<?php

namespace Core;

use Middleware\MiddlewareDispatcher;
use Middleware\LoggingMiddleware;
use Middleware\AuthMiddleware;
use Middleware\RouterMiddleware;
use Core\Routes;

class MiddlewareFabric
{

    public static function createForWeb(Router $router): MiddlewareDispatcher
    {
        $dispatcher = new MiddlewareDispatcher();
        
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());

        // Место для мидлваров веба


        $dispatcher->add(new RouterMiddleware($router));
        return $dispatcher;
    }
    
    public static function createForApi(Router $router): MiddlewareDispatcher
    {
        $dispatcher = new MiddlewareDispatcher();
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());
        
        // Место для мидлваров апи
        $r = Routes::$routes;
        $ex = [$r['signin']]; //массив исключений для проверки аунтефикации
        $dispatcher->add(new AuthMiddleware($ex));

        $dispatcher->add(new RouterMiddleware($router));
        return $dispatcher;
    }
}