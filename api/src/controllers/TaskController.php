<?php 
namespace Controllers;

use Classes\Check;
use Classes\Response;
use Classes\Route;
use Exception;
use Models\Base\StatusesQuery;
use Models\Map\TasksTableMap;
use Models\Tasks;
use Models\TasksQuery;

#[Route("/api/tasks")]
class TaskController{

    #[Route("","post")]
    public function TaskAdd($params){
        try{
            $fields = ["title", "description", "status"];
            $response = new Response();

            if(!Check::params($fields,$params)){
                $response->status = 400;
                $response->body = "required ".json_encode($params);
                return $response;
            }

            $sts = StatusesQuery::create()->find()->toArray();
            if($params["status"] > count($sts)){
                $response->status = 400;
                $response->body = "status id required ".json_encode($sts);
                return $response;
            }

            if(TasksQuery::create()->filterByTitle($params["title"])
            ->filterByDescription($params["description"])
            ->filterByStatus($params["status"])->exists()){
                $response->status = 409;
                $response->body = "Task with that parameters already exists";
                return $response;
            }

            $task = new Tasks();
            $task->setTitle($params["title"])
            ->setDescription($params["description"])
            ->setStatus($params["status"])->save();
            $response->body = "Task added with id = ".$task->getId();
            return $response;
        }
        catch(Exception $ex){
            $response->status = 500;
            $response->body = $ex->getMessage();
            return $response;
        }
    }

    #[Route("","get")]
    public function GetTasks(){
        try{
            $response = new Response();
            $response->body = TasksQuery::create()->find()->toArray();
            return $response;
        }
        catch(Exception $ex){
            $response->status = 500;
            $response->body = $ex->getMessage();
            return $response;
        }
    }

    #[Route("/{id}","get")]
    public function GetTaskById($params){
        try{
            $response = new Response();
            $id = $params["id"] ?? null;

            if($id === null){
                $response->status = 400;
                $response->body = "required id(int)";
                return $response;
            }

            $task = TasksQuery::create()->findById($id)->toArray();
            if($task == null){
                $response->status = 400;
                $response->body = "task with id = ".$id." not found or incorrect id";
                return $response;
            }

            $response->body = json_encode($task);
            return $response;
        }
        catch(Exception $ex){
            $response->status = 500;
            $response->body = $ex->getMessage();
            return $response;
        }
    }
}
?>