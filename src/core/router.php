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

    public function filterParams($params){
        if (is_array($params)) {
            return array_map([$this, 'filterParams'], $params);
        }
        $params = strip_tags($params);
        $params = htmlspecialchars($params, ENT_QUOTES, 'UTF-8');
        return $params;
    }

    public function dispatch(Request $request) {
        $uri = $request->uri;
        $method = $request->method;
        $path = parse_url($uri, PHP_URL_PATH);
        
        $queryParams = [];
        $queryString = parse_url($uri, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                preg_match_all('/\{([a-z]+)\}/', $route['path'], $paramNames);
                $params = [];
                
                for ($i = 0; $i < count($paramNames[1]); $i++) {
                    $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
                }
                
                $params = array_merge($params, $queryParams);
                $params = $this->filterParams($params);
                
                list($controller, $action) = explode('@', $route['handler']);
            
                if (strpos($controller, '\\') === false) {
                    $controller = "Api\\{$controller}";
                }
                
                $controllerInstance = new $controller();
                $result = $controllerInstance->$action($params, $request);
                
                if ($result instanceof Response) {
                    return $result;
                }
                
                return new Response(200, $result);
            }
        }
    
        return new Response(404, ['error' => 'Wrong api route']);
    }
}