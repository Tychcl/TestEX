<?php
namespace Api;

use Classes\Validate;
use Core\Response;
use EmptyIterator;
use Exception;
use Models\Category;
use Models\CategorylistQuery;
use Models\CategoryQuery;
use Models\TeacherQuery;
use PDOException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use ZipArchive;

class CategoryController{
    public static $base = '/api/category/';
    public static $method = ['add' => 'POST', 'delete' => 'DELETE','find' => 'GET'];

    public function add($params, $r){
        try{
            $teacher = $params['teacherid'] ?? null;
            $organ = $params['organ'] ?? null;
            $date = $params['date'] ?? null;
            $num = $params['num'] ?? null;
            $post = $params['post'] ?? null;
            $place = $params['place'] ?? null;
            $category = $params['categoryid'] ?? null;

            //return new Response(400, ['error' => $params]);

            if(empty($teacher)){
                session_start();
                $teacher = $_SESSION['id'];
            }

            if(empty($organ) || empty($date) || empty($num) ||
            empty($post) || empty($place) || empty($category) || empty($r->files['document']['name'])){
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

            $c = CategoryQuery::create()->
            filterByTeacherId($teacher)->
            filterByOrgan($organ)->
            filterByDate($date)->
            filterByNum($num)->
            filterByPost($post)->
            filterByPlace($place)->
            findOneByCategoryid($category);
            if($c){
                return new Response(400, ['error' => 'already exist']);
            }

            $c = new Category();
            $c->setTeacherId($teacher)->
            setOrgan($organ)->
            setDate($date)->
            setNum($num)->
            setPost($post)->
            setPlace($place)->
            setCategoryid($category)->save();
            
            $fio = TeacherQuery::create()->findOneById($teacher)->getFio();
            $directory = dirname(__DIR__,3).'/files/cats/';
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
            $previous = $e->getPrevious();
            if ($previous instanceof PDOException) {
                $ere = "SQL Error Code: " . $previous->getCode() . "\n"
                . "SQL Error Message: " . $previous->getMessage() . "\n"
                . "SQL State: " . $previous->errorInfo[0] . "\n"
                . "Driver Code: " . $previous->errorInfo[1] . "\n"
                . "Driver Message: " . $previous->errorInfo[2] . "\n";
            }
            return new Response(500, $e);
        }
    }
}
?>