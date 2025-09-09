<?php
namespace Api;

use Models\TeacherQuery;

class AuthController{
    
    public function signin($params)
    {
        $login = $params['login'] ?? "null";
        $pwd = $params['password'] ?? "null";

        echo $login."   ".$pwd;
    }

}
?>