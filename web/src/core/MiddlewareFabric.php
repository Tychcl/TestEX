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

        $dispatcher->add(new AuthMiddleware([], []));
        $dispatcher->add(new RouterMiddleware($router));
        return $dispatcher;
    }
    
    public static function createForApi(Router $router): MiddlewareDispatcher
    {
        //header("Access-Control-Allow-Origin: *");
        $dispatcher = new MiddlewareDispatcher();
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());
        
        // Место для мидлваров апи
        //массив исключений для проверки аунтефикации
        //массив для проверки на уровень доступа
        $ex = ['/api/user/signin']; //массив исключений для проверки аунтефикации
        $admin = ['/api/user/signup',
                '/api/user/delete',
                '/api/user/find',
                '/api/event/info/add',
                '/api/event/info/delete']; 
        $dispatcher->add(new AuthMiddleware($ex, $admin));

        $dispatcher->add(new RouterMiddleware($router));
        return $dispatcher;
    }
}