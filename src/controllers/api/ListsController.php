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
        $r = new Response();

        if (!in_array($list, $names)) {
            $r->status = 400;
            $r->body = ['error' => 'list name required or bad name',
                'names' => $names];
            return $r;
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
        
        $r->body = $elements;
        return $r;
    }
}

?>