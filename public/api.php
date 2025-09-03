<?php
use Core\Router;
use Api\ListsController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();
$router->add('GET', '/api/list/{list}', 'ListsController@show');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($requestMethod, $requestUri);
?>