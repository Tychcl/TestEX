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

class EventController{
    public static $base = '/web/event/';
    public static $method = ['add' => 'GET', 'delete' => 'GET','find' => 'GET'];

    public function add(){
        try{
            $infos = EventinfoQuery::create()->find()->toArray();
            $teachers = TeacherQuery::create()->select(['id', 'fio'])->find()->toArray();
            $roles =  EventroleQuery::create()->find()->toArray();
            $awards =  EventawarddegreeQuery::create()->find()->toArray();
            $html = Render::renderTemplate('event/add', 
            ['infos' => Render::create_options($infos),
             'teachers' => Render::create_options($teachers),
             'roles' => Render::create_options($roles),
             'awards' => Render::create_options($awards)]);
            return new Response(200, ['html'=>$html, 'js' => '_files/_scripts/_pages/championship.js']);
        }catch(Exception $e){
            return new Response(500, ['error'=>$e->getMessage()]);
        }
    }
}
?>