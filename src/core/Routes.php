<?php

namespace Core;

class Routes{

    public static $routes = [
        'signin' => '/api/user/signin',
        'lists' => '/api/list/{list}',
        'signup' => '/api/user/signup',
        'passwordchange' => '/api/user/passwordchange'
    ];

}

?>