<?php
namespace Web;

use Classes\Render;
use Core\Response;
use Exception;
use Models\EventlevelQuery;

class InfoController{
    public static $base = '/web/event/info/';
    public static $method = ['add' => 'GET', 'delete' => 'GET','find' => 'GET'];

    public function add($params){
        try{
            $levels = EventlevelQuery::create()->find()->toArray();
            $html = Render::renderTemplate('event/info/add', ['options' => Render::create_options($levels)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/info.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function delete($params){
        
    }

    public function find($params){
        
    }
}
?>