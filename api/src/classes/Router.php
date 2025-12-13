<?php
namespace Classes;

use ReflectionClass;
use ReflectionMethod;
use Attribute;
use Exception;
use Classes\Route;

class Router {
    private $routes = [];

    public function addController($controller) {
        #Получаем атрибут route для пути из контроллера
        $Reflect = new ReflectionClass($controller::class);
        $Route = $Reflect->getAttributes(Route::class)[0]->newInstance();
        $ConPath = $Route->path;

        $methods = get_class_methods($controller);
        foreach($methods as $m){
            $Reflect = new ReflectionMethod($m);
            $Route = $Reflect->getAttributes(Route::class)[0]->newInstance();

            $this->routes[] = [
                'method' => strtoupper($Route->method),
                'path' => strtolower($ConPath + $Route->path),
                'handler' => $controller.'@'.$m
            ];
        }
        
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
        $uri = strtolower(rtrim($request->uri, '/'));
        $method = $request->method;
        $path = parse_url($uri, PHP_URL_PATH);
        
        if (empty($path)) {
            $path = '/';
        }
        
        $jsonData = json_decode($request->body, true) ?? [];
        
        $queryParams = [];
        $queryString = parse_url($uri, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $routePath = rtrim($route['path'], '/');

            if (strpos($routePath, '{') === false) {
                if ($routePath === $path) {
                    $params = array_merge($queryParams, $jsonData, $request->params);
                    $params = $this->filterParams($params);
                    return $this->executeHandler($route, $params, $request);
                }
                continue;
            }
            
            $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                preg_match_all('/\{([a-z]+)\}/', $routePath, $paramNames);
                $params = [];
                
                for ($i = 0; $i < count($paramNames[1]); $i++) {
                    $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
                }
                
                $params = array_merge($params, $queryParams, $jsonData, $request->params);

                $params = $this->filterParams($params);
                
                return $this->executeHandler($route, $params, $request);
            }
        }
    
        return new Response(404, [
            'error' => 'Wrong api route or method',
            'request' =>[ 
            'uri' => $uri,
            'method' => $method],
            'path' => $path,
            'routes' => array_column($this->routes, 'path')
        ]);
    }
    
    private function executeHandler($route, $params, $request) {
        try{
            list($controller, $action) = explode('@', $route['handler']);
            
            if (strpos($controller, '\\') === false) {
                $controller = "Controllers\\{$controller}";
            }

            $controllerInstance = new $controller();
            $result = $controllerInstance->$action($params, $request);

            if ($result instanceof Response) {
                return $result;
            }

            return $result;
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}