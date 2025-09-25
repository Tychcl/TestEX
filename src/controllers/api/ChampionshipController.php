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

class ChampionshipController{

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

            if(EventinfoQuery::create()->filterByName($name)->filterByStart($start)->filterByEnd($end)->findByLevel($level)){
                return new Response(400, ['error' => 'already exists']);
            }

            $event = new Eventinfo();
            $event->setName($name)->setStart($start)->setEnd($end)->setLevel($level)->save();
            return new Response(200, 'success');
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function showList($params, Request $request){
        try{ 
            $map = [
                'award' => 'Models\EventawarddegreeQuery',
                'level' => 'Models\EventlevelQuery',
                'role' => 'Models\EventroleQuery'
            ];
            $id = $params['id'] ?? null;
            $name = $params['name'] ?? null;
            if(!$name || !in_array($name, array_keys($map))){
                return new Response(400, ['error' => 'wrong name or name required']);
            }

            return $this->show($map['role'], $id);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    private function show($list, $id = null){
        try{ 
            if($id){
                $a = $list::create()->findOneById($id);
                if($a){
                    return new Response(200, $a->toArray());
                }
            }
            return new Response(200, ['list' => $list::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>