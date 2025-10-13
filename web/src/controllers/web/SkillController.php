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
            $categorys = CategorylistQuery::create()->find()->toArray();
            $teachers = TeacherQuery::create()->select(['id', 'fio'])->find()->toArray();
            $html = Render::renderTemplate('skill/add');
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/category.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }
}
?>