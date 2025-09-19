<?php
use Core\Router;
use Core\Request;
use Core\MiddlewareFabric;
use Middleware\RouterMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/generated-conf/config.php';

$router = new Router();
$router->add('GET', '/api/list/{list}', 'Api\ListsController@show');
$router->add('GET', '/api/auth/signin', 'Api\AuthController@signin');
$router->add('GET', '/api/auth/signup', 'Api\AuthController@signup');

$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>