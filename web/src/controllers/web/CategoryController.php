<?php
namespace Web;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;
use Models\Categorylist;
use Models\CategorylistQuery;
use Models\TeacherQuery;

class CategoryController{
    public static $base = '/web/category/';
    public static $method = ['add' => 'GET', 'delete' => 'GET','find' => 'GET'];

    public function add($params){
        try{
            $categorys = CategorylistQuery::create()->find()->toArray();
            $teachers = TeacherQuery::create()->select(['id', 'fio'])->find()->toArray();
            $html = Render::renderTemplate('category/add', 
            ['categorys' => Render::create_options($categorys),'teachers'=>Render::create_options($teachers)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/category.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }
}
?>