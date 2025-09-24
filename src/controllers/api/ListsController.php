<?php

namespace Api;

use Core\Request;
use Core\Response;
use Models\CategorylistQuery;
use Models\EventawarddegreeQuery;
use Models\EventlevelQuery;
use Models\EventroleQuery;
use Models\UserroleQuery;

class ListsController {
    public function show($params, Request $request) {
        $names = ['categoryList', 'eventAwardDegree', 'eventLevel', 'eventRole', 'userRole'];
        $list = $params['list'] ?? null;

        if (!in_array($list, $names)) {
            return new Response(400, ['error' => 'list name required or bad name', 'names' => $names]);
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
        
        return new Response(400, $elements);
    }
}

?>