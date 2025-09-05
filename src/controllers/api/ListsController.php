<?php

namespace Api;

use Models\CategorylistQuery;
use Models\EventawarddegreeQuery;
use Models\EventlevelQuery;
use Models\EventroleQuery;
use Models\UserroleQuery;

class ListsController {
    public function show($params) {
        $names = ["categoryList", "eventAwardDegree", "eventLevel", "eventRole", "userRole"];
        $list = $params['list'] ?? null;
        
        if (!in_array($list, $names)) {
            http_response_code(400);
            header('Content-Type: application/json');
            return json_encode([
                "error" => "list name required or bad name",
                "names" => $names
            ]);
        }

        switch ($list) {
            case 'categoryList':
                $elements = CategorylistQuery::create()->find()->toArray();
                break;
            case 'userRole':
                $elements = UserRoleQuery::create()->find()->toArray();
                break;
            case 'eventAwardDegree':
                $elements = EventawarddegreeQuery::create()->find()->toArray();
                break;
            case 'eventLevel':
                $elements = EventlevelQuery::create()->find()->toArray();
                break;
            case 'eventRole':
                $elements = EventroleQuery::create()->find()->toArray();
                break;
            default:
                $elements = [];
        }
        
        header('Content-Type: application/json');
        return json_encode($elements);
    }
}

?>