<?php
session_start();
use Core\Request;
use Middleware\AuthMiddleware;
use Classes\Render;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

//echo Render::renderTemplate(null,[], 'test.php');
//
////echo trim('///user/signin', '/');
//return;
if(AuthMiddleware::isAuthenticated($_SESSION)){
    //echo json_encode($_SESSION);
    echo Render::renderTemplate(null,[], 'main.php');
}else{
    //echo json_encode($_SESSION);
    echo Render::renderTemplate('user/signin');
}
?>