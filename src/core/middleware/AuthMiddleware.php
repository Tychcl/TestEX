<?php

namespace Middleware;

use Core\Request;
use Core\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (!$this->isAuthenticated($request)) {
            $response = new Response();
            $response->status = 401;
            $response->body = 'Unauthorized';
            return $response;
        }
        return $next($request);
    }
    
    private function isAuthenticated(Request $request): bool
    {
        // код проверки jwt потом написать
        return isset($request->headers['Authorization']);
    }
}
?>