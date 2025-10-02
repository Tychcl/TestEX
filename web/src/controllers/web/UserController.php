<?php
namespace Api;
session_start();
use Core\Response;
use Core\JWToken;
use Core\Request;
use Models\Teacher;
use Models\TeacherQuery;
use Exception;
use Models\UserroleQuery;
use Classes\Validate;
use Models\Map\TeacherTableMap;
use Models\Userrole;

class UserController{
    
    public function signin($params){
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

    public function signup($params){
        try{

            $fio = $params['fio'] ?? null;
            $login = strtolower($params['login']) ?? null;
            $password = $params['password'] ?? null;
            $role = $params['roleid'] ?? null;

            if(!$fio || !$login|| !$password|| !$role){
                return new Response(400, ['error' => 'fio,login, password and roleid required']);
            }

            if(!Validate::fio($fio)){
                return new Response(400, ['error' => 'wrong pattern, example: Фамилия Имя Отчесво, only cyrillic']);
            }

            if(TeacherQuery::create()->findOneByLogin($login)) {
                return new Response(400, ['error' => 'user with that login already exist']);
            }

            if(!UserroleQuery::create()->findOneById($role)) {
                return new Response(400, ['error' => 'role by id not found']);
            }

            $teacher = new Teacher();
            $teacher->setFio($fio)->
            setLogin($login)->
            setPassword(password_hash($password, PASSWORD_DEFAULT))->
            setRoleid(intval($role))->save();
            return new Response(200, ['user' => $teacher->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function pwdChange($params){
        try{

            $password = $params['password'] ?? null;
            $confirm_password = $params['confirm'] ?? null;
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

                $teacher = TeacherQuery::create()->findOneById($_SESSION['id']);

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
            session_destroy();
            $_SESSION = array();
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

    public function userDelete($params){
        try{

            $id = $params['id'] ?? null;
            $login = $params['login'] ?? null;

            if(!$id || !$login){
                return new Response(400, ['error' => 'id, login required']);
            }

            $e = TeacherQuery::create()->filterById($id)->findOneByLogin($login);

            if(!$e){
                return new Response(400, ['error' => 'not found']);
            }
            
            $e->delete();
            return new Response(200, ['deleted']);

            return new Response(200, ['success']);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
        
    }

    public function userFind($params){
        try{
            $colums = TeacherTableMap::getTableMap()->getColumns();
            unset($colums["PASSWORD"]);
            $colums = array_keys($colums);
            $by = strtoupper($params['by']) ?? null;
            $value = $params['value'] ?? null;
            if($by && in_array($by, $colums) && $value){ 
                $e = TeacherQuery::create()->select($colums)->findOneBy($by, $value);
                if($e){
                    return new Response(200, ['user' => $e]);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value, can be lower or upper case', 'by' => $colums]);
            }
            return new Response(200, ['user' => TeacherQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function roleAdd($params){
         try{

            $name = $params['name'] ?? null;

            if(!$name){
                return new Response(400, ['error' => 'name required']);
            }

            if(UserroleQuery::create()->findOneByName($name)){
                return new Response(400, ['error' => 'already exists']);
            }
            
            $r = new Userrole();
            $r->setName($name)->save();

            return new Response(200, ['success' => $r->toArray()]);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function roleDelete($params){
         try{

            $id = intval($params['id']) ?? null;

            if(!$id){
                return new Response(400, ['error' => 'id required']);
            }

            if($id < 3){
                return new Response(400, ['error' => 'cant delete default roles']);
            }
            
            $r = UserroleQuery::create()->findById($id);

            if(!$r){
                return new Response(400, ['error' => 'not exists']);
            }

            $r->delete();

            return new Response(200, ['Deleted']);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function roleList($params){
        try{

            $colums = ['id', 'name'];
            $by = strtolower($params['by']) ?? null;
            $value = $params['value'] ?? null;

            if($by && in_array($by, $colums) && $value){ 
                $e = UserroleQuery::create()->findOneBy($by, $value);
                if($e){
                    return new Response(200, $e);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value', 'by' => $colums]);
            }
            return new Response(200, ['list' => UserroleQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>