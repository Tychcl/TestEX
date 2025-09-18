<?php
namespace Api;

use Core\Response;
use Core\JWToken;
use Models\TeacherQuery;
use Exception;

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
                    false, // secure - только HTTPS если пустить сайт в работу
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

}
?>