<?php
use Classes\HelloWorld;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$helloWorld = new HelloWorld();
$helloWorld->announce();
?>