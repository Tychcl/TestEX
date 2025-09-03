<?php

namespace Api;

use Models\CategorylistQuery;

require_once dirname(__DIR__) . '/vendor/autoload.php';

class ListsController{

    public function show($param){
        echo 'lists.php';
        $names = ["categoryList", "eventAwardDegree", "eventLevel", "eventRole", "userRole"];
        $list = $param['list'] ?? null;
        if(!in_array($list,$names)){
            return json_encode(["error" => "list name required or bad name",
            "names" => $names]);
        }
        $elements = CategorylistQuery::create()->find()->toArray();
        return json_encode($elements.$list);
    }
}

?>