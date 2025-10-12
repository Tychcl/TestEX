<?php

namespace Classes;

class Render{

    public static function create_options($array){
        $keys = array_keys($array[0]);
        $str = '';
        foreach($array as $e){
            $str = $str.'<option value="'.$e[$keys[0]].'">'.$e[$keys[1]].'</option>';
        }
        return $str;
    }

    public static function renderTemplate($templatedir = null, $data = [], $name = '/template.php') {
        extract($data);
        ob_start();
        if(!$templatedir){
            $name = trim($name, '/');
        }
        include dirname(__DIR__).'/page/'.trim($templatedir, '/').$name;
        return ob_get_clean();
    }

}

?>