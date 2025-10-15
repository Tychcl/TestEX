<?php
namespace Api;

use Classes\Validate;
use Core\Response;
use DateTime;
use Exception;
use Models\Eventinfo;
use Models\EventinfoQuery;
use Models\EventlevelQuery;
use Models\Map\EventinfoTableMap;
use ZipArchive;

class InfoController{
    public static $base = '/api/event/info/';
    public static $method = ['add' => 'POST', 'delete' => 'DELETE','find' => 'GET','getZip' => 'GET'];

    public function add($params){
        try{
            $name = $params['name'] ?? null;
            $start = $params['start'] ?? null;
            $end = $params['end'] ?? null;
            $level = $params['level'] ?? null;

            if(empty($name) || empty($start) || empty($level)){
                return new Response(400, ['error' => 'name, start(Y-m-d), end(Y-m-d) and levelid required. end bigger or equals start']);
            }

            if(!Validate::date($start)){
                return new Response(400, ['error' => 'wrong start(Y-m-d) format', 'value' => $start]);
            }

            if(!empty($end) && !Validate::date($end)){
                return new Response(400, ['error' => 'wrong end(Y-m-d) format']);
            }elseif(!empty($end)){
                $cend = DateTime::createFromFormat('Y-m-d', $end);
                $cstart = DateTime::createFromFormat('Y-m-d', $start);
                if($cend < $cstart){
                    return new Response(400, ['error' => 'end < start']);
                }
            }

            $l = EventlevelQuery::create()->findOneById($level);
            if(!$l){
                return new Response(400, ['error' => 'wrong levelid', 'list' => EventlevelQuery::create()->find()->toArray()]);
            }

            $directory = dirname(__DIR__,3).'/files/events/';
            $zipFileName = $name.'_'.$start.'_'.$end.'_'.$l->getName().'.zip';
            $zipPath = $directory . $zipFileName;
            $e = EventinfoQuery::create()->filterByName($name)->filterByStart($start)->filterByLevel($level)->findOneByZip($zipPath);
            //return new Response(400, ['error' => json_encode($e)]);
            if(!$e){
                $event = new Eventinfo();
                $event->setName($name)->setStart($start)->setEnd($end)->setLevel($level)->setZip($zipPath)->save();
                return new Response(200, ['success']);
            }else{
                return new Response(400, ['error' => 'already exists']);
            }
        }catch(Exception $e){
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function delete($params){
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

    public function find($params){
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

    public function getZip($params){
        $start = $params['start'] ?? null;
        $end = $params['end'] ?? null;

        if(empty($start) || empty($end)){
            return new Response(400, ['error' => 'not all data']);
        }

        if(!Validate::date($start) || !Validate::date($end)){
            return new Response(400, ['error' => 'wrong data format']);
        }

        $infos = EventinfoQuery::create()->select(['zip'])->
        where("start BETWEEN '$start' AND '$end'")->find()->toArray();
        if(empty($infos)){
            return new Response(400, ['error' => 'nothing found']);
        }

        $zipFileName = 'download.zip';
        $zipPath = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Не удалось создать архив");
        }
        foreach ($infos as $filePath) {
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipPath));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($zipPath);
        unlink($zipPath);
        exit;
    }
}
?>