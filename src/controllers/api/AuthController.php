<?php
namespace Api;

use Core\Response;
use Core\JWToken;
use Core\Request;
use Models\Teacher;
use Models\TeacherQuery;
use Exception;
use Models\UserroleQuery;

class AuthController{
    
    public function signin($params, Request $request)
    {
        $login = $params['login'] ?? null;
        $pwd = $params['password'] ?? null;

        if($login == null || $pwd == null){
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'Login and password required'];
            return $r;
        }
        try{
            $teacher = TeacherQuery::create()->findOneByLogin($login);
            if($teacher && password_verify($pwd, $teacher->getPassword())){
                $payload = [
                    'id' => $teacher->getId(),
                    'login' => $teacher->getLogin(),
                    'roleid' => $teacher->getRoleid()
                ];
                $token = JWToken::generateToken($payload);
                $r = new Response();
                $r->setCook(
                    'jwt', 
                    $token, 
                    time() + (60 * 60 * 6), // 6 часов
                    '/', 
                    '', 
                    false, // secure - только HTTPS если пустить сайт в работу нужно true
                    true, // httponly - недоступно через JavaScript
                    'Strict' // samesite
                );
                $r->status = 200;
                $r->body = ['success' => $payload];
                return $r;
            }else{
                $r = new Response();
                $r->status = 400;
                $r->body = ['error' => 'Invalid login or password'];
                return $r;
            }
        }catch(Exception $e){
            $r = new Response();
            $r->status = 500;
            $r->body = ['error' => $e->getMessage()];
            return $r;
        }
    }

    public function signup($params, Request $request)
    {
        $fio = $params['fio'] ?? null;
        $login = $params['login'] ?? null;
        $password = $params['password'] ?? null;
        $role = $params['roleid'] ?? null;

        if($request->jwt_payload['roleid'] != 1){
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'no access'];
            return $r;
        }
        
        if($fio == null || $login == null || $password == null || $role == null){
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'fio,login, password and roleid required'];
            return $r;
        }

        if(!preg_match('/[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*/', $fio)){
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'wrong pattern, example: Фамилия Имя Отчесво, only cyrillic'];
            return $r;
        }

        $roles = UserroleQuery::create();

        if(!$roles->findOneById($role)) {
            $r = new Response();
            $r->status = 400;
            $r->body = ['error' => 'role by id not found'];
            return $r;
        }

        $fio = explode(' ', $fio);
        $teacher = new Teacher()->setSurname($fio[0])->
        setName($fio[1])->
        setMidname($fio[2])->
        setLogin($login)->
        setPassword(password_hash($password, PASSWORD_DEFAULT));
    }

}
?>