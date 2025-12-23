<?php
namespace Api;

use Classes\Validate;
use Core\JWToken;
use Core\Response;
use Core\Route;
use Exception;
use Models\Users;
use Models\UsersQuery;
use Dotenv\Dotenv;

require_once dirname(__DIR__,3) . '/vendor/autoload.php';

#[Route("/api/users")]
class UserController
{
    #[Route("/regin", "POST", false)]
    public function regIn($params)
    {
        try{
            $fields = ['name', 'phone', 'email', 'password', 'confirm'];
            if(Validate::checkParams($fields, $params)){
                return new Response(400, ['error' => 'not all parameters']);
            }
            if($params['password'] != $params['confirm']){
                return new Response(400, ['error' => 'passwords not equals']);
            }
            if(Validate::phone(trim($params['phone']), $r)){
                return $r;
            }
            if(Validate::email(trim($params['email']), $r)){
                return $r;
            }
            if(validate::findUser(['name', 'phone', 'email'], $params, $r)){
                return $r;
            }
            array_pop($fields);
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            $user = new Users();
            foreach($fields as $field){
                $user->setByName(ucfirst($field), trim($params[$field]));
            }
            $user->save();
            return new Response(200, ['result' => 'successful']);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    private function check_captcha($token) {
        $ch = curl_init("https://smartcaptcha.cloud.yandex.ru/validate");
        $dotenv = Dotenv::createImmutable(dirname(__DIR__,3));
        $dotenv->load();
        $args = [
            "secret" => $_ENV['CAPTCHASERVERKEY'],
            "token" => $token,
            "ip" => $_SERVER['REMOTE_ADDR'], 
        ];
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POST, true);    
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch); 
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
            return true;
        }
    
        $resp = json_decode($server_output);
        return $resp->status === "ok";
    }

    #[Route("/signin", "POST", false)]
    public function signIn($params)
    {
        try{
            $login = $params['login'] ?? null;
            $password = $params['password'] ?? null;
            $captcha = $params['captcha'] ?? null;
            if(!$login | !$password){
                return new Response(400, ['error' => 'not all parameters']);
            }
            if(!$captcha){
                return new Response(400, ['error' => 'solve captcha']);
            }
            if(!$this->check_captcha($captcha)){
                return new Response(400, ['error' => 'invalid captcha']);
            }
            $u = UsersQuery::create();
            $r = new Response();
            if(!validate::phone($login, $r)){
                $u = $u->findOneByPhone($login);
            }
            else if(!Validate::email($login, $r)){
                $u = $u->findOneByEmail($login);
            }
            else{
                $r->body = ['error' => 'wrong email or phone format'];
                return $r;
            }
            if(!$u){
                return new Response(400, ['error' => 'wrong login or password']);
            }
            if(!password_verify($password, $u->getPassword())){
                return new Response(400, ['error' => 'wrong login or password']);
            }
            $payload = [
                'id' => $u->getId(),
                'phone' => $u->getPhone(),
                'email' => $u->getEmail()
            ];
            $token = JWToken::generateToken($payload);
            $r = new Response(200, ['success' => $payload]);
            $r->setCook(
                'jwt', 
                $token, 
                time() + (60 * 60 * 6),
                '/', 
                '', 
                false, 
                true, 
                'Strict'
            );
            session_start();
            $_SESSION = $payload;
            return $r;
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    #[Route("", "PUT")]
    public function edit($params)
    {
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $id = $_SESSION['id'] ?? null;
            $fields = ['name', 'phone', 'email'];
            $password = $params['currentPassword'] ?? null;
            if(!$id){
                return new Response(400, ['error' => 'null id, try signin']);
            }
            $u = UsersQuery::create()->findOneById($id);
            if(!$u){
                return new Response(400, ['error' => 'user not found']);
            }
            if(!$password){
                return new Response(400, ['error' => 'null password']);
            }
            if(!password_verify($password, $u->getPassword())){
                return new Response(400, ['error' => 'wrong password']);
            }
            foreach($fields as $field){
                $p = $params[$field] ?? null;
                if($p){
                    $u->setByName(ucfirst($field), $p);
                }
            }
            $u->save();
            return new Response(200, ['result' => 'successful']);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    #[Route("/password", "PUT")]
    public function changePassword($params)
    {
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $id = $_SESSION['id'] ?? null;
            $old = $params['old'] ?? null;
            $new = $params['new'] ?? null;
            $confirm = $params['confirm'] ?? null;
            if(!$id){
                return new Response(400, ['error' => 'null id, try signin']);
            }
            if(!$new | !$confirm | !$old){
                return new Response(400, ['error' => 'not all params']);
            }
            if($new != $confirm){
                return new Response(400, ['error' => 'new and confirm password not equals']);
            }
            $u = UsersQuery::create()->findOneById($id);
            if(!$u){
                return new Response(400, ['error' => 'user not found']);
            }
            if(!password_verify($old, $u->getPassword())){
                return new Response(400, ['error' => 'wrong old password']);
            }
            $u->setPassword(password_hash($new, PASSWORD_DEFAULT));
            $u->save();
            return new Response(200, ['result' => 'successful']);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    private function innerDelete($id){
        try{
            $u = UsersQuery::create()->findOneById($id);
            if(!$u){
                return new Response(400, ['error' => 'user not found']);
            }
            $u->delete();
            return new Response(200, ['result' => 'successful']);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    #[Route("", "DELETE")]
    public function delete()
    {
        try{
            session_start();
            $id = trim($_SESSION['id']) ?? null;
            if(!$id){
                return new Response(400, ['error' => 'null id, try signin']);
            }
            return $this->innerDelete($id);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    #[Route("/{id}", "DELETE")]
    public function deleteByID($params)
    {
        try{
            $id = $params['id'] ?? null;
            if(!$id){
                return new Response(400, ['error' => 'id required']);
            }
            session_start();
            if($_SESSION['id'] != $id){
                return new Response(400, ['error' => 'cant delete not your profile']);
            }
            return $this->innerDelete($id);
        }
        catch (Exception $ex){
            return Validate::Ex($ex);
        }
    }

    #[Route("/logout", "POST", false)]
    public function logout(){
        session_start();
        session_destroy();
        $_SESSION = [];
        $response = new Response(200, ['success' => true]);
        $response->setCook('jwt', '', time() - 3600, '/', '', true, true, 'Strict');
        return $response;
    }

}