<?php
use Core\Router;
use Core\Routes;
use Core\Request;
use Core\MiddlewareFabric;
use Middleware\RouterMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

$router = new Router();
//Пользователь
$router->addController('Api\UserController');
//Чемпионаты
$router->addController('Api\InfoController');
$router->addController('Api\EventController');
//Категории
$router->addController('Api\CategoryController');
//Категории
$router->addController('Api\SkillController');

$dispatcher = MiddlewareFabric::createForApi($router);

$request = new Request();
$response = $dispatcher->handle($request);
$response->send();
?>