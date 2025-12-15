<?php
namespace Classes;

class Check{
    public static function params($params, $request): bool{
        foreach($params as $param){
            $p = $request[$param] ?? null;
            //error_log($p."  ".$param."  ".json_encode($request));
            //error_log(!in_array($param, $request->array_keys()));
            //error_log($p === null);
            //error_log(trim($p === ''));
            if($p === null || trim($p === '')){
                return false;
            }
        }
        return true;
    }
}
?>