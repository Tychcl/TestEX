<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Classes\HelloWorld;

$hello = new HelloWorld();
$hello->announce();
?>