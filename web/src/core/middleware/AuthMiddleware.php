<?php

namespace Middleware;

use Models\TeacherQuery;
use Core\JWToken;
use Core\Request;
use Core\Response;
use Core\Router;
use Exception;

class AuthMiddleware implements MiddlewareInterface
{

    private $exception;
    private $admin;

    public function __construct($ex, $a)
    {
        $this->exception = $ex;
        $this->admin = $a;
    }

    public function handle(Request $request, callable $next): Response
    {
        session_start();
        $uri = parse_url($request->uri, PHP_URL_PATH);
        if($request->cookie['jwt'] && empty($_SESSION))
        {
            $_SESSION = JWToken::validateToken($request->cookie['jwt']) ?? null;;
        }elseif(!$request->cookie['jwt']){
            session_destroy();
            $_SESSION = array();
        }
        if (!in_array($uri, $this->exception) && !$this->isAuthenticated($_SESSION)) {
            return new Response(401, ['error'=>'Unauthorized']);
        }
        if(in_array($uri, $this->admin) && $_SESSION['roleid'] != 1){
            return new Response(400, ['error' => 'no access']);
        }
        return $next($request);
    }
    
    public static function isAuthenticated($payload): bool
    {
        try{
            $v = TeacherQuery::create()->filterById(intval($payload['id']))->
            filterByLogin($payload['login'])->
            filterByRoleid(intval($payload['roleid']))->
            findOne();
            if($v){
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }
}
?>