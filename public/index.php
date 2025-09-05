<?php
use Classes\HelloWorld;
use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$helloWorld = new HelloWorld();
$helloWorld->announce();
?>