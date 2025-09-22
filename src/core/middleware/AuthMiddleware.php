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

    private array $exception;

    public function __construct(array $ex)
    {
        $this->exception = $ex;
    }

    public function handle(Request $request, callable $next): Response
    {
        if (!in_array(parse_url($request->uri, PHP_URL_PATH), $this->exception) && !$this->isAuthenticated($request)) {
            return new Response(401, ['error'=>'Unauthorized']);
        }
        return $next($request);
    }
    
    private function isAuthenticated(Request $request): bool
    {
        try{
            if($request->cookie['jwt'])
            {
                $payload = JWToken::validateToken($request->cookie['jwt']);
                $v = TeacherQuery::create()->filterById(intval($payload['id']))->
                filterByLogin($payload['login'])->
                filterByRoleid(intval($payload['roleid']))->
                findOne();
                if($v){
                    $request->jwt_payload = $payload;
                    return true;
                }
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }
}
?>