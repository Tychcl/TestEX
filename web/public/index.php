<?php
use Html\event\info\Info;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/propel/generated/conf/config.php';

//$host = 'db'; // Имя вашего контейнера с БД
//$user = 'root'; // Ваш пользователь БД
//$password = 'strong_password_123'; // Ваш пароль
//$database = 'teacherCompetence'; // Имя вашей БД
//
//try {
//    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Соединение с базой данных установлено успешно.\n";
//    $stmt = $pdo->query("SHOW TABLES");
//    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
//    
//    echo "Таблицы в базе данных:\n";
//    print_r($tables);
//} catch(PDOException $e) {
//    echo "Ошибка подключения: " . $e->getMessage();
//}

$i = new Info();
echo $i->add();
?>