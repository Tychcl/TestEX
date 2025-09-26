<?php
namespace Api;

use Core\Response;
use Core\Request;
use DateTime;
use Exception;
use GPBMetadata\Google\Type\Datetime as TypeDatetime;
use Models\Eventawarddegree;
use Models\EventawarddegreeQuery;
use Models\Eventinfo;
use Models\EventinfoQuery;
use Models\EventlevelQuery;
use Models\EventQuery;
use Models\Map\TeacherTableMap;
use Models\TeacherQuery;

class ChampionshipController{

    private $models = [
                'award' => 'Models\EventawarddegreeQuery',
                'level' => 'Models\EventlevelQuery',
                'role' => 'Models\EventroleQuery'
            ];

    private $map = [
                'award' => 'Models\Map\EventawarddegreeTableMap',
                'level' => 'Models\Map\EventlevelTableMap',
                'role' => 'Models\Map\EventroleTableMap'
            ];

    public function add($params, Request $request){
        try{
            $name = $params['name'] ?? null;
            $start = $params['start'] ?? null;
            $end = $params['end'] ?? null;
            $level = $params['levelid'] ?? null;

            if(!$name || !$start|| !$end|| !$level){
                return new Response(400, ['error' => 'name, start(Timestamp), end(Timestamp) and levelid required. end bigger or equals start']);
            }

            if($end < $start){
                return new Response(400, ['error' => 'end < start']);
            }

            if(!EventlevelQuery::create()->findOneById($level)){
                return new Response(400, ['error' => 'wrong levelid', 'list' => EventlevelQuery::create()->find()->toArray()]);
            }

            if(EventinfoQuery::create()->filterByName($name)->filterByStart($start)->filterByEnd($end)->findOneByLevel($level)){
                return new Response(400, ['error' => 'already exists']);
            }

            $event = new Eventinfo();
            $event->setName($name)->setStart($start)->setEnd($end)->setLevel($level)->save();
            return new Response(200, 'success');
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function delete($params, Request $request){
        try{
            if($request->jwt_payload['roleid'] != 1){
                return new Response(400, ['error' => 'no access']);
            }

            $id = $params['id'] ?? null;

            if(!$id){
                return new Response(400, ['error' => 'id required']);
            }

            $e = EventinfoQuery::create()->findOneById($id);

            if(!$e){
                return new Response(400, ['error' => 'not found']);
            }
            
            $e->delete();
            return new Response(200, ['deleted']);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
        
    }

    public function showList($params, Request $request){
        try{
            $colums = ['id', 'name'];
            $by = strtolower($params['by']) ?? null;
            $value = $params['value'] ?? null;
            $name = $params['name'] ?? null;

            if(!$name || !in_array($name, array_keys($this->models))){
                return new Response(400, ['error' => 'wrong name or name required']);
            }

            $list = $this->models[$name];

            if($by && in_array($by, $colums) && $value){ 
                $e = $list::create()->findOneBy($by, $value);
                if($e){
                    return new Response(200, $e);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value']);
            }
            return new Response(200, $list::create()->find()->toArray());
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}
?>