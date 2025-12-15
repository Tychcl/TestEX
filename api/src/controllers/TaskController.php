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
}
?>