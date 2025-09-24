<?php

namespace Core;

class Routes{

    public static $api = [
        //user auth routes
        'signup' => ['method' => 'GET','path' => '/api/user/signup', 'handler' => 'Api\AuthController@signup'],
        'signin' => ['method' => 'GET', 'path' => '/api/user/signin', 'handler' => 'Api\AuthController@signin'],
        'signout' => ['method' => 'GET', 'path' => '/api/user/signout', 'handler' => 'Api\AuthController@signout'],
        'passwordchange' => ['method' => 'GET', 'path' => '/api/user/passwordchange', 'handler' => 'Api\AuthController@passwordchange'],
        //get all lists
        'lists' => ['method' => 'GET', 'path' => '/api/list/{list}', 'handler' => 'Api\ListsController@show'],
        //event
        'eventinfoadd' => ['method' => 'GET', 'path' => '/api/event/new', 'handler' => 'Api\ChampionshipController@add'],
        'eventShow' => ['method' => 'GET', 'path' => '/api/event/{name}', 'handler' => 'Api\ChampionshipController@showList'],
    ];

    public static $web = [
        'signin' => '/api/user/signin',
        'lists' => '/api/list/{list}',
        'signup' => '/api/user/signup',
        'passwordchange' => '/api/user/passwordchange'
    ];

}

?>