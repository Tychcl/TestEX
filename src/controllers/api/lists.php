<?php

namespace Api;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Classes\Connect;

class ListsController{

    public function show($param){
        $list = $param['list'] ?? null;
        if(!$list){
            return json_encode(["error" => "list name required",
            "names" => ["categoryList", "eventAwardDegree", "eventLevel", "eventRole", "userRole"]]);
        }
        if(!preg_match("/^[a-zA-Z.]/",$list)){
            return json_encode(["error" => "bad list name",
            "names" => ["categoryList", "eventAwardDegree", "eventLevel", "eventRole", "userRole"]]);
        }

        $con = new Connect();
        if($con->getConn() == null){
            return json_encode(["error" => "Database null connection"]);
        }

        $q = $con->query("select * from ".$list);
        if($q == null){
            return json_encode(["error" => "Database query error"]);
        }
        
        $arr = array();
    }
}

?>