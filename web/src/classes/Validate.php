<?php

namespace Classes;

class Validate{

    public static function fio($fio, &$matches = null){
        return preg_match('/[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*/', $fio, $matches);
    }

    public static function format($format){
        return in_array($format, ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/doc', 'application/docx']);
    }

    public static function imageformat($format){
        return in_array($format, ['image/jpeg', 'image/png', 'image/jpg']);
    }

    public static function appformat($format){
        return in_array($format, ['application/pdf', 'application/doc', 'application/docx']);
    }
}

?>