<?php
use Classes\HelloWorld;

require_once dirname(__DIR__) . '/vendor/autoload.php';

echo json_encode(DateTime::createFromFormat('Y-d-m','2025-24-09'));
echo json_encode(strtotime(stripslashes('2025-24-09')));
?>