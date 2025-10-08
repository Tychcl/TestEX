<?php
namespace Web;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;
use Models\EventinfoQuery;
use Models\EventroleQuery;

class ChampionshipController{

    public function infoAdd(){
        try{
            $q = EventlevelQuery::create()->find()->toArray();
            $options = '';
            foreach($q as $e){
                $options = $options.'<option value="'.$e['Id'].'">'.$e['Name'].'</option>';
            }
            $html = Render::renderTemplate('event/info/add', ['options' => $options]);
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
            $i = EventinfoQuery::create()->find()->toArray();
            $infos = '';
            foreach($i as $e){
                $infos = $infos.'<option value="'.$e['Id'].'">'.$e['Name'].'</option>';
            }
            $r =  EventroleQuery::create()->find()->toArray();
            $roles = '';
            foreach($r as $e){
                $roles = $roles.'<option value="'.$e['Id'].'">'.$e['Name'].'</option>';
            }

            $html = Render::renderTemplate('event/add', ['infos' => $infos, 'roles' => $roles]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/championship.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function showList($params){
        
    }

}
?>