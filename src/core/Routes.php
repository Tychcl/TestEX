<?php

namespace Core;

class Routes{

    public static $api = [
        //такие "/api/event/{name}" апи пути в api.php всегда должны быть последними из свое группы для добавлнеия в роутер
        //user auth routes
        'userSignup' => ['method' => 'POST','path' => '/api/user/signup', 'handler' => 'Api\UserController@signup'],
        'userSignin' => ['method' => 'POST', 'path' => '/api/user/signin', 'handler' => 'Api\UserController@signin'],
        'userSignout' => ['method' => 'POST', 'path' => '/api/user/signout', 'handler' => 'Api\UserController@signout'],
        'userPasswordChange' => ['method' => 'PUT', 'path' => '/api/user/passwordchange', 'handler' => 'Api\UserController@passwordChange'],
        'userDelete' => ['method' => 'DELETE', 'path' => '/api/user/delete', 'handler' => 'Api\UserController@userDelete'],
        'userShow' => ['method' => 'GET', 'path' => '/api/user/show', 'handler' => 'Api\UserController@userDelete'],
        //event
        'eventinfoadd' => ['method' => 'GET', 'path' => '/api/event/new', 'handler' => 'Api\ChampionshipController@add'],
        'eventListShow' => ['method' => 'GET', 'path' => '/api/event/{name}', 'handler' => 'Api\ChampionshipController@showList'],
    ];

    public static $web = [
        'signin' => '/api/user/signin',
        'lists' => '/api/list/{list}',
        'signup' => '/api/user/signup',
        'passwordchange' => '/api/user/passwordchange'
    ];

}

?>