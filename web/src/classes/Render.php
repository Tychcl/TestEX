<?php

namespace Classes;

class Render{

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