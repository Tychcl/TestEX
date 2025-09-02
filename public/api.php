<?php
use Core\Router;
use Api\ListsController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();
$router->add('GET', '/api/list/{list}', 'ListsController@show')

?>