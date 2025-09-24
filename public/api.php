<?php
use Core\Router;
use Core\Routes;
use Core\Request;
use Core\MiddlewareFabric;
use Middleware\RouterMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

$router = new Router();
$r = Routes::$api;
//user
$router->add($r['signin']);
$router->add($r['signup']);
$router->add($r['signout']);
$router->add($r['passwordchange']);
//event
#$router->add($r['eventShow']);

$router->add($r['eventAward']);
$router->add($r['eventLevel']);
$router->add($r['eventRole']);

$router->add($r['eventinfoadd']);
$router->add($r['lists']);



$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>