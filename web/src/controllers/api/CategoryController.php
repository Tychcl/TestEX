<?php
namespace Api;

use Classes\Validate;
use Core\Response;
use EmptyIterator;
use Exception;
use Models\CategorylistQuery;
use Models\TeacherQuery;
use ZipArchive;

session_start();
class CategoryController{
    public static $base = '/api/category/';
    public static $method = ['add' => 'POST', 'delete' => 'DELETE','find' => 'GET'];

    public function add($params, $r){
        $teacher = $params['teacherid'] ?? $_SESSION['id'];
        $organ = $params['organ'] ?? null;
        $date = $params['date'] ?? null;
        $num = $params['num'] ?? null;
        $post = $params['post'] ?? null;
        $place = $params['place'] ?? null;
        $category = $params['categoryid'] ?? null;

        return new Response(400, [$params, $r->files]);

        if(empty($organ) || empty($date) || empty($num) ||
        empty($post) || empty($place) || empty($category) || empty($r->files['name'])){
            return new Response(400, ['error' => 'not all data']);
        }

        if(!Validate::date($date)){
            return new Response(400, ['error' => 'wrong data format']);
        }

        if(!Validate::digits($num)){
            return new Response(400, ['error' => 'wrong num format']);
        }

        if(!CategorylistQuery::create()->findOneById($category)){
            return new Response(400, ['error' => 'wrong category id']);
        }

        $fio = TeacherQuery::create()->findOneById($teacher)->getFio();
        $directory = dirname(__DIR__,3).'/files/events/';
        $zipPath = $directory.$fio.'.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                try{
                    $teacherFileName = $r->files['teacher']['name']['file'];
                    $teacherTempPath = $r->files['teacher']['tmp_name']['file'];
                    $zip->addFile($teacherTempPath, $teacherFolder . '/' . $teacherFileName);
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
        
    }
}
?>