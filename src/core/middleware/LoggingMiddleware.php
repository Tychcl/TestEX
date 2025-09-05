<?php

namespace Middleware;

use Core\Request;
use Core\Response;

class LoggingMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        error_log("Request: {$request->method} {$request->uri}");
        $response = $next($request);
        error_log("Response: {$response->status}");
        return $response;
    }
}
?>