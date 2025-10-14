<?php
namespace Api;

use Core\Response;
use Exception;
use Models\EventlevelQuery;
use Classes\Render;
use Classes\Validate;
use Models\Categorylist;
use Models\CategorylistQuery;
use Models\Skill;
use Models\SkillQuery;
use Models\TeacherQuery;
use ZipArchive;

class SkillController{
    public static $base = '/api/skill/';
    public static $method = ['add' => 'POST', 'delete' => 'GET','find' => 'GET'];

    public function add($params, $r){
        try{
            $teacher = $params['teacherid'] ?? null;

            $num = $params['num'] ?? null;
            $regnum = $params['regnum'] ?? null;
            $hours = $params['hours'] ?? null;

            $date = $params['date'] ?? null;
            $start = $params['start'] ?? null;
            $end = $params['end'] ?? null;

            $city = $params['city'] ?? null;
            $place = $params['place'] ?? null;
            $theme = $params['theme'] ?? null;
            $director = $params['director'] ?? null;
            $secretary = $params['secretary'] ?? null;

            if(empty($teacher)){
                session_start();
                $teacher = $_SESSION['id'];
            }

            if(empty($num) || empty($regnum) || empty($hours) || 
            empty($r->files['document']['name']) || empty($date) ||empty($start) || empty($end) ||
            empty($city) || empty($place) || empty($theme) || empty($director) || empty($secretary)){
                return new Response(400, ['error' => 'not all data']);
            }

            if(!Validate::date($date) || !Validate::date($start) || !Validate::date($end)){
                return new Response(400, ['error' => 'wrong data format']);
            }

            if(!Validate::digits($num) || !Validate::digits($regnum) || !Validate::digits($hours)){
                return new Response(400, ['error' => 'wrong number format']);
            }

            $c = SkillQuery::create()->
            filterByTeacherid($teacher)->
            filterByNum($num)->
            filterByRegnum($regnum)->
            filterByCity($city)->
            filterByDate($date)->
            filterByPlace($place)->
            filterByStart($start)->
            filterByEnd($end)->
            filterByTheme($theme)->
            filterByHours($hours)->
            filterByDirector($director)->
            findOneBySecretary($secretary);
            if($c){
                return new Response(400, ['error' => 'already exist']);
            }

            $n = new Skill();
            $n->setTeacherid($teacher)->
            setNum($num)->
            setRegnum($regnum)->
            setCity($city)->
            setDate($date)->
            setPlace($place)->
            setStart($start)->
            setEnd($end)->
            setTheme($theme)->
            setHours($hours)->
            setDirector($director)->
            setSecretary($secretary)->
            save();

            $fio = TeacherQuery::create()->findOneById($teacher)->getFio();
            $directory = dirname(__DIR__,3).'/files/skills/';
            $zipPath = $directory.$fio.'.zip';
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                try{
                    $teacherFileName = $r->files['document']['name'];
                    $teacherTempPath = $r->files['document']['tmp_name'];
                    $zip->addFile($teacherTempPath, $teacherFileName);
                    $zip->close();
                }catch(Exception $e){
                    $zip->close();
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }
                    throw $e;
                }
            } else {
                throw new Exception('Не удалось создать zip-архив');
            }
            return new Response(200, ['successfully' => 'Category registered']);
        }catch(Exception $e){
            return new Response(500, $e->getMessage());
        }
    }
}
?>