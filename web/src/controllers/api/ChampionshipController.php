<?php
namespace Api;

use Core\Response;
use Core\Request;
use DateTime;
use Exception;
use Models\Eventawarddegree;
use Models\EventawarddegreeQuery;
use Models\Eventinfo;
use Models\EventinfoQuery;
use Models\EventlevelQuery;
use Models\EventQuery;
use Models\Map\EventinfoTableMap;
use Models\Map\TeacherTableMap;
use Models\TeacherQuery;

class ChampionshipController{

    private $models = [
                'award' => 'Models\EventawarddegreeQuery',
                'level' => 'Models\EventlevelQuery',
                'role' => 'Models\EventroleQuery'
            ];

    public function infoAdd($params){
        try{
            $name = $params['name'] ?? null;
            $start = date("Y-m-d", $params['start']) ?? null;
            $end = date("Y-m-d", $params['end']) ?? null;
            $level = $params['level'] ?? null;

            if(!$name || !$start|| !$end|| !$level){
                return new Response(400, ['error' => 'name, start(Timestamp), end(Timestamp) and levelid required. end bigger or equals start']);
            }

            if($end < $start){
                return new Response(400, ['error' => 'end < start']);
            }

            if(!EventlevelQuery::create()->findOneById($level)){
                return new Response(400, ['error' => 'wrong levelid', 'list' => EventlevelQuery::create()->find()->toArray()]);
            }

            $e = EventinfoQuery::create()->filterByName($name)->filterByStart($start)->filterByEnd($end)->findOneByLevel($level);
            if($e){
                return new Response(400, ['error' => 'already exists']);
            }

            $event = new Eventinfo();
            $event->setName($name)->setStart($start)->setEnd($end)->setLevel($level)->save();
            return new Response(200, ['success']);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function infoFind($params){
        try{
            $colums = array_keys(EventinfoTableMap::getTableMap()->getColumns());
            $by = strtoupper($params['by']) ?? null;
            $value = $params['value'] ?? null;
            if($by && in_array($by, $colums) && $value){ 
                $e = EventinfoQuery::create()->select($colums)->findOneBy($by, $value);
                if($e){
                    return new Response(200, ['info' => $e]);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value, can be lower or upper case', 'by' => $colums]);
            }
            return new Response(200, ['info' => EventinfoQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function infoDelete($params){
        try{

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

    public function eventAdd($params, $r){
        $infoid = $params['info'] ?? null;
        $teacher = $params['teacher'] ?? null;
        $students = $params['students'] ?? null;

        //$r = new Request();
        //return new Response(400, ['file' => $r->files, 'params' => $params] );
    }

    public function showList($params){
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
                return new Response(400, ['error' => 'wrong by or value', 'by' => $colums]);
            }
            return new Response(200, ['list'=>$list::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>