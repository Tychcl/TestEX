<?php

namespace Classes;

class Validate{

    public static $formats = [
        'doc' => "application/msword",
        'docx' => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        'pdf' => "application/pdf"
    ];

    public static function fio($fio, &$matches = null){
        return preg_match('/[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*/', $fio, $matches);
    }

    public static function date($date){
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
    }

    public static function digits($str){
        return preg_match('/^\d+$/', $str);
    }

    public static function format($format){
        return in_array($format, ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/doc', 'application/docx']);
    }

    public static function imageformat($format){
        return in_array($format, ['image/jpeg', 'image/png', 'image/jpg']);
    }

    public static function appformat($format){
        return in_array($format, array_values(Validate::$formats));
    }


}

?>