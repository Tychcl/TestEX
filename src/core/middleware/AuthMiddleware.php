<?php

namespace Middleware;

use Core\Request;
use Core\Response;
use Core\Router;

class AuthMiddleware implements MiddlewareInterface
{

    private array $exception;

    public function __construct(array $ex)
    {
        $this->exception = $ex;
    }

    public function handle(Request $request, callable $next): Response
    {
        if (!in_array(parse_url($request->uri, PHP_URL_PATH), $this->exception) && !$this->isAuthenticated($request)) {
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