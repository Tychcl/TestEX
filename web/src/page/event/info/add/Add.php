<?php 
namespace Html;

use Models\EventlevelQuery;

class Add {

    public static function show(){
        $levels = EventlevelQuery::create()->find()->toArray();
    }

}


?>