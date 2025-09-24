<?php
namespace Api;

use Core\Response;
use Core\JWToken;
use Core\Request;
use Models\Teacher;
use Models\TeacherQuery;
use Exception;
use Models\UserroleQuery;
use Classes\Validate;

class AuthController{
    
    public function signin($params, Request $request)
    {
        $login = strtolower($params['login']) ?? null;
        $pwd = $params['password'] ?? null;

        if(!$login || !$pwd){
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
        try{
            $fio = $params['fio'] ?? null;
            $login = strtolower($params['login']) ?? null;
            $password = $params['password'] ?? null;
            $role = $params['roleid'] ?? null;

            if($request->jwt_payload['roleid'] != 1){
                return new Response(400, ['error' => 'no access']);
            }

            if(!$fio || !$login|| !$password|| !$role){
                return new Response(400, ['error' => 'fio,login, password and roleid required']);
            }

            if(!Validate::fio($fio)){
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
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function passwordchange($params, Request $request){
        try{

            $password = $params['password'] ?? null;
            $confirm_password = $params['confirmpassword'] ?? null;
            $new_password = $params['newpassword'] ?? null;

            if(!$password|| !$confirm_password|| !$new_password){
                return new Response(400, ['error' => 'Password, confirmpassword and newpassword required']);
            }

            if($new_password != $confirm_password){
                return new Response(400, ['error' => 'Passwords are not equals']);
            }

            if($password == $new_password){
                return new Response(400, ['error' => 'You already use this password']);
            }


                $teacher = TeacherQuery::create()->findOneById($request->jwt_payload['id']);

                if(!password_verify($password, $teacher->getPassword())){
                    return new Response(400, ['error' => 'Wrong password']);
                }

                $teacher->setPassword(password_hash($new_password, PASSWORD_DEFAULT))->save();

                return new Response(200, ['Password successfully changed']);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function signout(){
        try{
            $r = new Response(200, ['success']);
            $r->setCook(
                'jwt', 
                '', 
                time(), // 0 часов
                '/', 
                '', 
                false, // secure - только HTTPS если пустить сайт в работу нужно true
                true, // httponly - недоступно через JavaScript
                'Strict' // samesite
            );
            return $r;
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>