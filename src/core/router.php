<?php

namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
    foreach ($this->routes as $route) {
        // Проверяем соответствие метода HTTP
        if ($route['method'] !== $method) {
            continue;
        }
        
        // Преобразуем паттерн маршрута в регулярное выражение
        $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $route['path']);
        $pattern = '#^' . $pattern . '$#';
        echo $pattern.' ___ '.$uri;
        if (preg_match($pattern, $uri, $matches)) {
            preg_match_all('/\{([a-z]+)\}/', $route['path'], $paramNames);
            
            $params = [];
            for ($i = 0; $i < count($paramNames[1]); $i++) {
                $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
            }
            
            list($controller, $action) = explode('@', $route['handler']);
            $controllerInstance = new $controller();
            $response = $controllerInstance->$action($params);
            
            echo $response;
            return;
        }
    }
    
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
    }
}
?>