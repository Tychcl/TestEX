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
        $login = strtolower($params['login']) ?? null;
        $pwd = $params['password'] ?? null;

        if($login == null || $pwd == null){
            return new Response(400, ['error' => 'Login and password required']);
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
                $r = new Response(200, ['success' => $payload]);
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
                return $r;
            }else{
                return new Response(400, ['error' => 'Invalid login or password']);
            }
        }catch(Exception $e){
            return new Response( 500, ['error' => $e->getMessage()]);
        }
    }

    public function signup($params, Request $request)
    {
        $fio = $params['fio'] ?? null;
        $login = strtolower($params['login']) ?? null;
        $password = $params['password'] ?? null;
        $role = $params['roleid'] ?? null;

        if($request->jwt_payload['roleid'] != 1){
            return new Response(400, ['error' => 'no access']);
        }
        
        if($fio == null || $login == null || $password == null || $role == null){
            return new Response(400, ['error' => 'fio,login, password and roleid required']);
        }

        if(!preg_match('/[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*/', $fio)){
            return new Response(400, ['error' => 'wrong pattern, example: Фамилия Имя Отчесво, only cyrillic']);
        }

        if(TeacherQuery::create()->findOneByLogin($login)) {
            return new Response(400, ['error' => 'user with login '.$login.' already exist']);
        }

        if(!UserroleQuery::create()->findOneById($role)) {
            return new Response(400, ['error' => 'role by id not found']);
        }

        $teacher = new Teacher();
        $teacher->setFio($fio)->
        setLogin($login)->
        setPassword(password_hash($password, PASSWORD_DEFAULT))->
        setRoleid(intval($role))->save();

        return new Response(200, ['user succefuly created']);
    }

}
?>