<?php
namespace Classes;

use Core\Response;
use Exception;
use Models\UsersQuery;

class Validate{

    public static function Ex(Exception $ex){
        return new Response(500,$ex->getMessage());
    }

    public static function checkParams($params, $request): bool{
        foreach($params as $param){
            $p = $request[$param] ?? null;
            if($p === null || trim($p) === ''){
                return true;
            }
        }
        return false;
    }

    public static function phone($phone, &$response){
        if(preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $phone)){
            return false;
        }
        $response = new Response(400, ['error' => 'wrong phone format']);
        return true;
    }

    public static function email($email, &$response){
        if(preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i", $email)){
            return false;
        }
        $response = new Response(400, ['error' => 'wrong email format']);
        return true;
    }

    public static function findUser($fields, $params, &$response){
        foreach($fields as $field){
            $u = UsersQuery::create()->findOneBy(ucfirst($field), $params[$field]);
            if($u){
                $response = new Response(409, ['error' => 'user with that params already exists', 'params' => $fields]);
                return true;
            }
        }
        return false;
    }
}
?>