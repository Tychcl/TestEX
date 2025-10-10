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
$router->add($r['user']['signin']);
$router->add($r['user']['signup']);
$router->add($r['user']['signout']);
$router->add($r['user']['passwordChange']);
$router->add($r['user']['delete']);
$router->add($r['user']['find']);
//user role
$router->add($r['user']['role']['add']);
$router->add($r['user']['role']['delete']);
$router->add($r['user']['role']['find']);
//event
$router->add($r['event']['add']);
//event info
$router->add($r['event']['info']['add']);
$router->add($r['event']['info']['delete']);
$router->add($r['event']['info']['find']);
$router->add($r['event']['listShow']);//всегда последнее в группе event
//category


$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>