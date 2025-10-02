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
        $dispatcher = new MiddlewareDispatcher();
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());
        
        // Место для мидлваров апи
        $r = Routes::$api;
        //массив исключений для проверки аунтефикации
        //массив для проверки на уровень доступа
        $ex = [$r['user']['signin']['path']]; //массив исключений для проверки аунтефикации
        $admin = [$r['user']['signup']['path'],
                $r['user']['delete']['path'],
                $r['user']['find']['path'],
                $r['user']['role']['add']['path'],
                $r['user']['role']['delete']['path'],
                $r['user']['role']['list']['path'],
                $r['event']['info']['delete']['path']]; 
        $dispatcher->add(new AuthMiddleware($ex, $admin));

        $dispatcher->add(new RouterMiddleware($router));
        return $dispatcher;
    }
}