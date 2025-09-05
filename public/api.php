<?php
use Core\Router;
use Classes\AnyList;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/generated-conf/config.php';

//// Проверяем, что классы могут быть загружены
//if (class_exists('Api\ListsController')) {
//    echo "Api\\ListsController class exists!\n";
//} else {
//    echo "Api\\ListsController class NOT found!\n";
//}
//
//if (class_exists('Core\Router')) {
//    echo "Core\\Router class exists!\n";
//} else {
//    echo "Core\\Router class NOT found!\n";
//}

$router = new Router();
$router->add('GET', '/api/list/{list}', 'Api\ListsController@show');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$r = $router->dispatch($requestMethod, $requestUri);
?>