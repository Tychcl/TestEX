<?php
session_start();
use Core\Request;
use Middleware\AuthMiddleware;
use Classes\Render;
use Core\JWToken;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

//echo Render::renderTemplate(null,[], 'test.php');
//
////echo trim('///user/signin', '/');
//return;

if(!$_COOKIE['jwt']){
    echo Render::renderTemplate('user/signin');
    return;
}
$_SESSION = JWToken::validateToken($_COOKIE['jwt']) ?? null;
if(empty($_SESSION)){
    echo Render::renderTemplate('user/signin');
    return;
}
echo Render::renderTemplate(null,[], 'main.php');
?>