<?php

namespace Core;

class Routes{

    public static $api = [
        //такие "/api/event/{name}" апи пути в api.php всегда должны быть последними из свое группы для добавлнеия в роутер
        //user auth routes
        'user' => [
            'signup' => ['method' => 'POST','path' => '/api/user/signup', 'handler' => 'Api\UserController@signup'],
            'signin' => ['method' => 'POST', 'path' => '/api/user/signin', 'handler' => 'Api\UserController@signin'],
            'signout' => ['method' => 'POST', 'path' => '/api/user/signout', 'handler' => 'Api\UserController@signout'],
            'passwordChange' => ['method' => 'PUT', 'path' => '/api/user/passwordchange', 'handler' => 'Api\UserController@passwordChange'],
            'delete' => ['method' => 'DELETE', 'path' => '/api/user/delete', 'handler' => 'Api\UserController@userDelete'],
            'find' => ['method' => 'GET', 'path' => '/api/user/find', 'handler' => 'Api\UserController@find'],
            'roleList' => ['method' => 'GET', 'path' => '/api/user/role', 'handler' => 'Api\UserController@showList']
        ],
        'event' => [
            'infoAdd' => ['method' => 'POST', 'path' => '/api/event/new', 'handler' => 'Api\ChampionshipController@add'],
            'infoDelete' => ['method' => 'DELETE', 'path' => '/api/event/delete', 'handler' => 'Api\ChampionshipController@delete'],
            'listShow' => ['method' => 'GET', 'path' => '/api/event/{name}', 'handler' => 'Api\ChampionshipController@showList'],
        ]
        //event
        
    ];

    public static $web = [
        'signin' => '/api/user/signin',
        'lists' => '/api/list/{list}',
        'signup' => '/api/user/signup',
        'passwordchange' => '/api/user/passwordchange'
    ];

}

?>