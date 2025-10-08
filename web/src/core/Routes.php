<?php

namespace Core;

class Routes{

    public static $api = [
        //такие "/api/event/{name}" апи пути в api.php всегда должны быть последними из свое группы для добавлнеия в роутер
        //user auth routes
        'user' => [
            'signup' => ['method' => 'POST','path' => '/api/user/signup', 'handler' => 'Api\UserController@signup'],
            'signin' => ['method' => 'POST', 'path' => '/api/user/signin', 'handler' => 'Api\UserController@signin'],
            'signout' => ['method' => 'get', 'path' => '/api/user/signout', 'handler' => 'Api\UserController@signout'],
            'passwordChange' => ['method' => 'PUT', 'path' => '/api/user/passwordchange', 'handler' => 'Api\UserController@pwdChange'],
            'delete' => ['method' => 'DELETE', 'path' => '/api/user/delete', 'handler' => 'Api\UserController@userDelete'],
            'find' => ['method' => 'GET', 'path' => '/api/user/find', 'handler' => 'Api\UserController@userFind'],
            'role' => [
                'add' => ['method' => 'POST', 'path' => '/api/user/role/add', 'handler' => 'Api\UserController@roleAdd'],
                'find' => ['method' => 'GET', 'path' => '/api/user/role/find', 'handler' => 'Api\UserController@roleFind'],
                'delete' => ['method' => 'DELETE', 'path' => '/api/user/role/delete', 'handler' => 'Api\UserController@roleDelete'],
                ]
        ],
        'event' => [
            'add' => [],
            'find' => [],
            'delete' => [],
            'info' => [
                'add' => ['method' => 'POST', 'path' => '/api/event/info/add', 'handler' => 'Api\ChampionshipController@infoAdd'],
                'delete' => ['method' => 'DELETE', 'path' => '/api/event/info/delete', 'handler' => 'Api\ChampionshipController@infoDelete'],
                'find' => ['method' => 'GET', 'path' => '/api/event/info/find', 'handler' => 'Api\ChampionshipController@infoFind']
            ],
            'listShow' => ['method' => 'GET', 'path' => '/api/event/{name}', 'handler' => 'Api\ChampionshipController@showList'],
        ]
        //event
        
    ];

    public static $web = [
        'user' => [
            'signup' => ['method' => 'GET','path' => '/web/user/signup', 'handler' => 'Api\UserController@signup'],
            'signin' => ['method' => 'GET', 'path' => '/web/user/signin', 'handler' => 'Api\UserController@signin'],
            'signout' => ['method' => 'GET', 'path' => '/web/user/signout', 'handler' => 'Api\UserController@signout'],
            'passwordChange' => ['method' => 'GET', 'path' => '/web/user/passwordchange', 'handler' => 'Api\UserController@pwdChange'],
            'delete' => ['method' => 'GET', 'path' => '/web/user/delete', 'handler' => 'Api\UserController@userDelete'],
            'find' => ['method' => 'GET', 'path' => '/web/user/find', 'handler' => 'Api\UserController@userFind'],
            'role' => [
                'add' => ['method' => 'GET', 'path' => '/web/user/role/add', 'handler' => 'Api\UserController@roleAdd'],
                'delete' => ['method' => 'GET', 'path' => '/web/user/role/delete', 'handler' => 'Api\UserController@roleDelete'],
                'list' => ['method' => 'GET', 'path' => '/web/user/role/list', 'handler' => 'Api\UserController@roleList']
            ]
        ],
        'event' => [
            'info' => [
                'add' => ['method' => 'GET', 'path' => '/web/event/info/add', 'handler' => 'Web\ChampionshipController@infoAdd'],
                'delete' => ['method' => 'GET', 'path' => '/web/event/info/delete', 'handler' => 'Api\ChampionshipController@infoDelete'],
                'find' => ['method' => 'GET', 'path' => '/web/event/info/find', 'handler' => 'Api\ChampionshipController@infoFind']
            ],
            'add' => ['method' => 'GET', 'path' => '/web/event/add', 'handler' => 'Web\ChampionshipController@eventAdd'],
            'listShow' => ['method' => 'GET', 'path' => '/web/event/{name}', 'handler' => 'Api\ChampionshipController@showList'],
        ]
    ];

}

?>