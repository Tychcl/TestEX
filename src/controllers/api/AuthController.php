<?php
namespace Api;

use Core\Response;
use Models\TeacherQuery;
use Dotenv\Dotenv;

class AuthController{
    
    public function signin($params)
    {
        $login = $params['login'] ?? null;
        $pwd = $params['password'] ?? null;

        if($login == null || $pwd == null){
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'Login and password required'];
            return $r;
        }

        $teacher = TeacherQuery::create()->findOneByLogin($login);
        if($teacher){
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->load();
            $hashed = hash_hmac('sha256', $teacher->getPassword(), $_ENV['KEY']);

        }else{
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'Invalid login or password'];
            return $r;
        }

    }

}
?>