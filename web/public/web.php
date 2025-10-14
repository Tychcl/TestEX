<?php
use Core\Router;
use Core\Routes;
use Core\Request;
use Core\MiddlewareFabric;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

$router = new Router();
$router->addController('Web\InfoController');
$router->addController('Web\EventController');
$router->addController('Web\CategoryController');
$router->addController('Web\SkillController');
$router->addController('Web\UserController');

$dispatcher = MiddlewareFabric::createForWeb($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>