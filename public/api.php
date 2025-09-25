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
$router->add($r['userSignin']);
$router->add($r['userSignup']);
$router->add($r['userSignout']);
$router->add($r['userPasswordChange']);
$router->add($r['userDelete']);
$router->add($r['userFind']);
//event
$router->add($r['eventinfoadd']);
$router->add($r['eventListShow']);//всегда последнее в группе event
//$router->add($r['lists']);



$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>