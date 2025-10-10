<?php
namespace Api;

use Classes\Validate;
use Core\Response;
use Core\Request;
use DateTime;
use Exception;
use Models\Event;
use Models\Eventawarddegree;
use Models\EventawarddegreeQuery;
use Models\Eventinfo;
use Models\EventinfoQuery;
use Models\Eventlevel;
use Models\EventlevelQuery;
use Models\EventQuery;
use Models\EventroleQuery;
use Models\Map\EventinfoTableMap;
use Models\Map\TeacherTableMap;
use Models\Student;
use Models\StudentQuery;
use Models\TeacherQuery;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ChampionshipController{

    private $models = [
                'award' => 'Models\EventawarddegreeQuery',
                'level' => 'Models\EventlevelQuery',
                'role' => 'Models\EventroleQuery'
            ];

    public function infoAdd($params){
        try{
            $name = $params['name'] ?? null;
            $start = date("Y-m-d", $params['start']) ?? null;
            $end = $params['end'] ?? null;
            $level = $params['level'] ?? null;

            if(!$name || !$start|| !$level){
                return new Response(400, ['error' => 'name, start(Timestamp), end(Timestamp) and levelid required. end bigger or equals start']);
            }

            if($end && $end < $start){
                return new Response(400, ['error' => 'end < start']);
            }elseif(!$end){
                $end = null;
            }

            if(!EventlevelQuery::create()->findOneById($level)){
                return new Response(400, ['error' => 'wrong levelid', 'list' => EventlevelQuery::create()->find()->toArray()]);
            }

            $e = EventinfoQuery::create()->filterByName($name)->filterByStart($start)->filterByEnd($end)->findOneByLevel($level);
            if($e){
                return new Response(400, ['error' => 'already exists']);
            }

            $event = new Eventinfo();
            $event->setName($name)->setStart($start)->setEnd($end)->setLevel($level)->save();
            return new Response(200, ['success']);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function infoFind($params){
        try{
            $colums = array_keys(EventinfoTableMap::getTableMap()->getColumns());
            $by = strtoupper($params['by']) ?? null;
            $value = $params['value'] ?? null;
            if($by && in_array($by, $colums) && $value){ 
                $e = EventinfoQuery::create()->select($colums)->findOneBy($by, $value);
                if($e){
                    return new Response(200, ['info' => $e]);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value, can be lower or upper case', 'by' => $colums]);
            }
            return new Response(200, ['info' => EventinfoQuery::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function infoDelete($params){
        try{

            $id = $params['id'] ?? null;

            if(!$id){
                return new Response(400, ['error' => 'id required']);
            }

            $e = EventinfoQuery::create()->findOneById($id);

            if(!$e){
                return new Response(400, ['error' => 'not found']);
            }
            
            $e->delete();
            return new Response(200, ['deleted']);

        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
        
    }

    public function eventAdd($params, $r){
        function check($a, $s){
            if(!$a['fio'] || !Validate::fio($a['fio']) || !$a[$s]){
                return false;
            }
            return true;
        }
        try{
            $info = $params['info'] ?? null;
            $teacher = $params['teacher'] ?? null;
            $teacherid = $params['teacher']['id'] ?? false;
            $teacherrole = $params['teacher']['role'] ?? null;
            $students = $params['students'] ?? null;
            //return new Response(400, ['file' => $r->files, 'params' => $params] );

            if(!$info || !$students || !$teacher || !$teacherrole
            || !$r->files['teacher'] || !$r->files['students'] || count($r->files['students']['error']) != count($students)){
                return new Response(400, ['error' => 'not all data']);
            }

            
            $eventinfo = EventinfoQuery::create()->findOneById(intval($info));
            if(!$eventinfo){
                return new Response(400, ['error' => "info with $info id dont exists"]);
            }

            foreach(array_values($students) as $i => $s){
                if(!check($s, 'award')){
                    $i += 1;
                    return new Response(400, ['error' => "wrong student $i data"]);
                }
            }

            foreach(array_values($r->files['students']['type']) as $i => $f){
                if(!Validate::appformat($f['file'])){
                    $i += 1;
                    return new Response(400, ['error' => "wrong student $i file type", 'type' => $f, 'access types' => array_keys(Validate::$formats)]);
                }
            }

            if(!Validate::appformat($r->files['teacher']['type']['file'])){
                $i += 1;
                return new Response(400, ['error' => "wrong teacher file type"]);
            }

            if(!$teacher['role'] || !EventroleQuery::create()->findOneById($teacher['role'])){
                return new Response(400, ['error' => 'wrong teacher role data']);
            }

            $teacherinfo = TeacherQuery::create()->findOneById($teacherid);
            if(!empty($teacherid) & !$teacherinfo){
                return new Response(400, ['error' => $teacher['id'].' wrong teacher id, profile not exists', 'teacher' => $teacherinfo]);
            }elseif(!$teacher['id']){
                session_start();
                $teacherinfo = TeacherQuery::create()->findOneById($_SESSION['id']);
            }

            $directory = dirname(__DIR__,3).'/files/events/';
            $teacherFolder = $teacherinfo->getFio();

            $zipFileName = $eventinfo->getName().'_'.
            $eventinfo->getStart('Y-m-d').'_'.
            $eventinfo->getEnd('Y-m-d').'_'.
            EventlevelQuery::create()->findOneById($eventinfo->getLevel())->getName().'.zip';

            $zipPath = $directory . $zipFileName;
            $zip = new ZipArchive();
            //return new Response(400, $zipPath);
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                try{
                    $teacherFileName = $r->files['teacher']['name']['file'];
                    $teacherTempPath = $r->files['teacher']['tmp_name']['file'];
                    $zip->addFile($teacherTempPath, $teacherFolder . '/' . $teacherFileName);
                    
                    $studentFiles = $r->files['students'];
                    foreach ($students as $i => $student) {
                       $studentFolder = $teacherFolder . '/' . $student['fio'] . '/';

                        if (isset($studentFiles['name'][$i]['file'])) {
                            $studentFileName = $studentFiles['name'][$i]['file'];
                            $studentTempPath = $studentFiles['tmp_name'][$i]['file'];
                            $zip->addFile($studentTempPath, $studentFolder . $studentFileName);
                        }

                        if(!StudentQuery::create()->findOneByFio($student['fio'])){
                            $s = new Student();
                            $s->setFio($student['fio'])->save();
                        }

                        $s = StudentQuery::create()->findOneByFio($student['fio'])->getId();
                        if(!EventQuery::create()->filterByInfoid($info)->
                            filterByTeacherid($teacherinfo->getId())->
                            filterByTeacherroleid($teacherrole)->
                            filterByStudentid($s)->
                            filterByAwardid($student['award'])->
                            findOneByDocument($zipPath)){

                            $event = new Event();
                            $event->setInfoid($info)->
                            setTeacherid($teacherinfo->getId())->
                            setTeacherroleid($teacherrole)->
                            setStudentid($s)->
                            setAwardid($student['award'])->
                            setDocument($zipPath)->save();
                        }
                        
                    }
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
            return new Response(200, ['successfully' => 'Участие добавлено']);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function showList($params){
        try{
            $colums = ['id', 'name'];
            $by = strtolower($params['by']) ?? null;
            $value = $params['value'] ?? null;
            $name = $params['name'] ?? null;

            if(!$name || !in_array($name, array_keys($this->models))){
                return new Response(400, ['error' => 'wrong name or name required']);
            }

            $list = $this->models[$name];

            if($by && in_array($by, $colums) && $value){ 
                $e = $list::create()->findOneBy($by, $value);
                if($e){
                    return new Response(200, $e);
                }
                return new Response(400, ['error' => 'not found']);
            }elseif($by || $value){
                return new Response(400, ['error' => 'wrong by or value', 'by' => $colums]);
            }
            return new Response(200, ['list'=>$list::create()->find()->toArray()]);
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

}
?>