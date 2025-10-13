<?php
session_start();
use Core\Request;
use Middleware\AuthMiddleware;
use Classes\Render;
use Core\JWToken;
use Models\EventlevelQuery;
use Models\TeacherQuery;
use Api\TestController;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

$navar = [
    'Чемпионат' => [
        ['func' => 'infoadd()',
        'svg' => 'plus',
        'name' => 'Добавить'],
        ['func' => 'eventadd()',
        'svg' => 'championship',
        'name' => 'Учет'],
    ],
    'Категория' => [
        ['func' => 'catadd()',
        'svg' => 'file',
        'name' => 'Учет'],
    ],
    'Квалификация' => [
        ['func' => 'skilladd()',
        'svg' => 'file',
        'name' => 'Учет'],
    ],
    'Пользователь' => [
        ['func' => 'signout()',
        'svg' => 'logout',
        'name' => 'Выйти'],
    ]
];

$admin = [

];
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

//if($_SESSION['roleid'] == 1){
//    $navar = $admin;
//}
$nav = '';
foreach(array_keys($navar) as $name){
    $buttons = '';
    foreach($navar[$name] as $button){
        $buttons = $buttons.Render::renderTemplate('sidebar', $button, '/button.php');
    }
    $nav = $nav.Render::renderTemplate('sidebar', ['name' => $name, 'buttons' => $buttons], '/folder.php');
}
echo Render::renderTemplate(null,['nav' => $nav], 'main.php');
?>