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
$router->add($r['user']['roleList']);
//event
$router->add($r['event']['infoAdd']);
$router->add($r['event']['infoDelete']);
$router->add($r['event']['listShow']);//всегда последнее в группе event
//$router->add($r['lists']);



$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>