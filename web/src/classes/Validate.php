<?php

namespace Classes;

class Validate{

    public static function fio($fio, &$matches = null){
        return preg_match('/[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*/', $fio, $matches);
    }
}

?>