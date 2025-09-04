<?php

namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                preg_match_all('/\{([a-z]+)\}/', $route['path'], $paramNames);
                $params = [];
                
                for ($i = 0; $i < count($paramNames[1]); $i++) {
                    $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
                }
                
                list($controller, $action) = explode('@', $route['handler']);
                
                if (strpos($controller, '\\') === false) {
                    $controller = "Api\\{$controller}";
                }
                
                $controllerInstance = new $controller();
                $response = $controllerInstance->$action($params);
                
                echo $response;
                return;
            }
        }
        
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
}
?>