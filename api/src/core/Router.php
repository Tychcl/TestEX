<?php
namespace Core;

use ReflectionClass;
use ReflectionMethod;
use Attribute;
use Exception;
use Core\Route;
use Core\Request;

class Router {

    public function filterParams($params){
        if (is_array($params)) {
            return array_map([$this, 'filterParams'], $params);
        }
        $params = strip_tags($params);
        $params = htmlspecialchars($params, ENT_QUOTES, 'UTF-8');
        return $params;
    }

    public function dispatch(Request $request) {
        $route = $request->route;
        $uri = strtolower(rtrim($request->uri, '/'));
        $path = parse_url($uri, PHP_URL_PATH);
        
        if (empty($path)) {
            $path = '/';
        }
        
        $jsonData = json_decode($request->body, true) ?? [];
        #error_log($request->body);
        $queryParams = [];
        $queryString = parse_url($uri, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }
        $routePath = rtrim($route['path'], '/');
        if (strpos($routePath, '{') === false) {
            $params = array_merge($queryParams, $jsonData, $request->params);
            $params = $this->filterParams($params);
            return $this->executeHandler($route, $params, $request);
        }
        preg_match_all('/\{([a-z]+)\}/', $routePath, $paramNames);
        $params = [];
        
        for ($i = 0; $i < count($paramNames[1]); $i++) {
            $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
        }
        
        $params = array_merge($params, $queryParams, $jsonData, $request->params);
        $params = $this->filterParams($params);
        
        return $this->executeHandler($route, $params, $request);
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