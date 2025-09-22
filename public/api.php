<?php
use Core\Router;
use Core\Routes;
use Core\Request;
use Core\MiddlewareFabric;
use Middleware\RouterMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

$router = new Router();
$r = Routes::$routes;
$router->add('GET', $r['signin'], 'Api\AuthController@signin');
$router->add('GET', $r['lists'], 'Api\ListsController@show');
$router->add('GET', $r['signup'], 'Api\AuthController@signup');
$router->add('GET', $r['passwordchange'], 'Api\AuthController@passwordchange');

$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>