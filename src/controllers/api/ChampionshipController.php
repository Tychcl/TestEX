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

    $map = [
        'award' => EventawarddegreeQuery::create()->find()->toArray(),
        'level' => EventlevelQuery::create()->find()->toArray(),
        'role' => EventlevelQuery::create()->find()->toArray()
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
            $id = $params['id'] ?? null;
            $name = $params['name'] ?? null;

            if(!$name){
                return new Response(400, ['error' => 'name required']);
            }
            switch($name){
                
            }
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function show($list, $id){
        try{ 
            if($id){
                $a = $list->findOneById($id);
                if($a){
                    return new Response(200, $a->toArray());
                }
            }
            return new Response(200, ['list' => $list->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function showAward($params, Request $request){
        try{ 
            $id = $params['id'] ?? null;
            if($id){
                $a = EventawarddegreeQuery::create()->findOneById($id)->toArray();
                if($a){
                    return new Response(200, $a);
                }
            }
            return new Response(200, ['list' => EventawarddegreeQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function showLevel($params, Request $request){
        try{ 
            $id = $params['id'] ?? null;
            if($id){
                $a = EventlevelQuery::create()->findOneById($id);
                if($a){
                    return new Response(200, $a->toArray());
                }
                else{}
            }
            return new Response(200, ['list' => EventlevelQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function showRole($params, Request $request){
        try{ 
            $id = $params['id'] ?? null;
            if($id){
                $a = EventlevelQuery::create()->findOneById($id)->toArray();
                if($a){
                    return new Response(200, $a);
                }
            }
            return new Response(200, ['list' => EventlevelQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>