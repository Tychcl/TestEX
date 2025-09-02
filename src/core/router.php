<?php

namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->buildPattern($path)
        ];
    }

    private function buildPattern($path) {
        return preg_replace('/\{[a-z]+\}/', '([^/]+)', $path);
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            // Проверяем соответствие метода
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Проверяем соответствие пути
            if (preg_match("#^{$route['pattern']}$#", $requestUri, $matches)) {
                // Извлекаем параметры из пути
                $pathParams = $this->extractPathParams($route['path'], $matches);
                
                // Объединяем с параметрами из query string
                $allParams = array_merge($pathParams, $_GET);
                
                // Вызываем обработчик
                list($controller, $action) = explode('@', $route['handler']);
                $controllerInstance = new $controller();
                return $controllerInstance->$action($allParams);
            }
        }
        
        // Если маршрут не найден
        http_response_code(404);
        return json_encode(['error' => 'Not found']);
    }

    private function extractPathParams($path, $matches) {
        $params = [];
        
        preg_match_all('/\{([a-z]+)\}/', $path, $paramNames);
        
        if (count($matches) > 1) {
            array_shift($matches);
            foreach ($paramNames[1] as $index => $name) {
                if (isset($matches[$index])) {
                    $params[$name] = $matches[$index];
                }
            }
        }
        
        return $params;
    }
}
?>