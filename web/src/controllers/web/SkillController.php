<?php
namespace Web;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;
use Models\Categorylist;
use Models\CategorylistQuery;
use Models\TeacherQuery;

class SkillController{
    public static $base = '/web/skill/';
    public static $method = ['add' => 'GET', 'delete' => 'GET','find' => 'GET'];

    public function add($params){
        try{
            $teachers = TeacherQuery::create()->select(['id', 'fio'])->find()->toArray();
            $html = Render::renderTemplate('skill/add',['teachers'=>Render::create_options($teachers)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/skill.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }
}
?>