<?php
namespace Web;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;
use Models\EventawarddegreeQuery;
use Models\EventinfoQuery;
use Models\EventroleQuery;
use Models\TeacherQuery;

class ChampionshipController{

    private function create_options($array){
        $keys = array_keys($array[0]);
        $str = '';
        foreach($array as $e){
            $str = $str.'<option value="'.$e[$keys[0]].'">'.$e[$keys[1]].'</option>';
        }
        return $str;
    }

    public function infoAdd(){
        try{
            $levels = EventlevelQuery::create()->find()->toArray();
            $html = Render::renderTemplate('event/info/add', ['options' => $this->create_options($levels)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/info.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function infoFind($params){
       
    }

    public function infoDelete($params){
        
    }

    public function eventAdd($params){
        try{
            $infos = EventinfoQuery::create()->find()->toArray();
            $teachers = TeacherQuery::create()->select(['id', 'fio'])->find()->toArray();
            $roles =  EventroleQuery::create()->find()->toArray();
            $awards =  EventawarddegreeQuery::create()->find()->toArray();
            $html = Render::renderTemplate('event/add', 
            ['infos' => $this->create_options($infos),
             'teachers' => $this->create_options($teachers),
             'roles' => $this->create_options($roles),
             'awards' => $this->create_options($awards)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/championship.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function showList($params){
        
    }

}
?>