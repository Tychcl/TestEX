<?php 
namespace Controllers;

use Classes\Check;
use Classes\Response;
use Classes\Route;
use Exception;
use Models\Map\TasksTableMap;
use Models\Tasks;
use Models\TasksQuery;

#[Route("/api/tasks")]
class TaskController{

    const WAITING = "Ожидает";
    const IN_PROGRESS = "В процессе";
    const DONE = "Выполнено";

    public static $status =
    [
        self::WAITING => "Ожидает",
        self::IN_PROGRESS => "В процессе",
        self::DONE => "Выполнено"
    ];

    #[Route("","get")]
    public function TaskAdd($params){
        try{
            $fields = ["title", "description", "status"];
            $response = new Response();
            if(!Check::params($fields,$params)){
                $response->status = 400;
                $response->body = "required ".json_encode($fields);
                return $response;
            }
            $response->body = self::$status[$params["status"]];
            return $response;
            $task = new Tasks();
            $task->setTitle($params["title"])
            ->setDescription($params["description"])
            ->setStatus(self::$status[$params["status"]])->save();
        }
        catch(Exception $ex){
            $response->status = 500;
            $response->body = $ex->getMessage();
            return $response;
        }
    }
}
?>