<?php
namespace Web;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;

class ChampionshipController{

    public function infoAdd(){
        try{
            $q = EventlevelQuery::create()->find()->toArray();
            $options = '';
            foreach($q as $e){
                $options = $options.'<option value="'.$e['Id'].'">'.$e['Name'].'</option>';
            }
            //echo json_encode($options);
            //<option value="">-- Выберите категорию --</option>
            $html = Render::renderTemplate('event/info/add', ['script' => '_files/_scripts/championship.js','options' => $options]);
            return new Response(200, ['html'=>$html]);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function infoFind($params){
       
    }

    public function infoDelete($params){
        
    }

    public function eventAdd($params){
        
    }

    public function showList($params){
        
    }

}
?>