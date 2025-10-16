<?php
namespace Web;

use Classes\Render;
use Core\Response;
use Exception;
use Models\TeacherQuery;
use Models\UserroleQuery;

session_start();

class UserController{
    public static $base = '/web/user/';
    public static $method = ['profile' => 'GET', 'add' => 'GET','find' => 'GET'];

    public function profile(){
        try{
            session_start();
            $teacher = TeacherQuery::create()->findOneById($_SESSION['id']);
            $fio = $teacher->getFio();
            $role = $teacher->getRoleid() == 1?'администратора':'преподавателя';
            $cat = $teacher->isCanupdcategory() == 1?'<a href="#" onclick="catadd()">можно обновить данные</a>':'все в порядке';
            $skill = $teacher->getNeedupdskill() == 1?'<a href="#" onclick="skilladd()">нужно обновить данные</a>':'все в порядке';
            $export = $teacher->getRoleid() == 1?Render::renderTemplate('user/profile',[],'/export.php') :'';
            $html = Render::renderTemplate('user/profile', ['fio' => $fio, 'role'=> $role, 'cat'=> $cat,'skill'=> $skill, 'export' => $export]);
            return new Response(200, ['html'=>$html, 'js' => $teacher->getRoleid() == 1?'_files/_scripts/_pages/profile.js':'']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }

    public function add(){
        try{
            $roles = UserroleQuery::create()->find()->toArray();
            $html = Render::renderTemplate('user/signup', ['roles' => Render::create_options($roles)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/signup.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }
}
?>