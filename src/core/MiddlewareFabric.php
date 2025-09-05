<?php

namespace Core;

use Middleware\MiddlewareDispatcher;
use Middleware\LoggingMiddleware;
use Middleware\AuthMiddleware;

class MiddlewareFabric
{


    public static function createForWeb(): MiddlewareDispatcher
    {
        $dispatcher = new MiddlewareDispatcher();
        
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());

        // Место для мидлваров веба
        
        return $dispatcher;
    }
    
    public static function createForApi(): MiddlewareDispatcher
    {
        $dispatcher = new MiddlewareDispatcher();
        
        // Место для общих мидваров
        $dispatcher->add(new LoggingMiddleware());
        
         // Место для мидлваров апи
        $dispatcher->add(new AuthMiddleware());

        return $dispatcher;
    }
}