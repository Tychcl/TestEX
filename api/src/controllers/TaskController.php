<?php 
namespace Controllers;

use Classes\Check;
use Classes\Response;
use Classes\Route;
use Exception;
use GrahamCampbell\ResultType\Success;
use Models\Base\StatusesQuery;
use Models\Map\TasksTableMap;
use Models\Tasks;
use Models\TasksQuery;
use Symfony\Component\Console\Tester\Constraint\CommandIsSuccessful;

#[Route("/api/tasks")]
class TaskController{

    #[Route("","POST")]
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

    #[Route("","GET")]
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

    #[Route("/{id}","GET")]
    public function GetTaskById($params){
        try{
            $response = new Response();
            $id = $params["id"] ?? null;

            if($id === null){
                $response->status = 400;
                $response->body = "required id(int)";
                return $response;
            }

            $task = TasksQuery::create()->findOneById($id);
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

    #[Route("/{id}","PUT")]
    public function EditTaskById($params){
        try{
            $response = new Response();
            $id = $params["id"] ?? null;

            if($id === null){
                $response->status = 400;
                $response->body = "required id(int)";
                return $response;
            }

            $sts = StatusesQuery::create()->find()->toArray();
            if($params["status"] > count($sts)){
                $response->status = 400;
                $response->body = "wrong status id ".json_encode($sts);
                return $response;
            }

            $task = TasksQuery::create()->findOneById($id);
            if($task === null){
                $response->status = 400;
                $response->body = "task with id = ".$id." not found or incorrect id";
                return $response;
            }

            foreach ($params as $key => $value) {
                if ($value === null) continue;

                match ($key) {
                    'title' => $task->setTitle($value),
                    'description' => $task->setDescription($value),
                    'status' => $value != 0 ? $task->setStatus($value) : null,
                    default => null
                };
            }

            $task->save();
            $response->body = "task successful updated";
            return $response;
        }
        catch(Exception $ex){
            $response->status = 500;
            $response->body = $ex->getMessage();
            return $response;
        }
    }

    #[Route("/{id}","DELETE")]
    public function DeleteTaskById($params){
        try{
            $response = new Response();
            $id = $params["id"] ?? null;

            if($id === null){
                $response->status = 400;
                $response->body = "required id(int)";
                return $response;
            }

            $task = TasksQuery::create()->findOneById($id);
            if($task == null){
                $response->status = 400;
                $response->body = "task with id = ".$id." not found or incorrect id";
                return $response;
            }

            $task->delete();
            $response->body = "Task deleted";
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