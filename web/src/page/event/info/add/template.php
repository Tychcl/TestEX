<?php
$script = file_get_contents(__DIR__.'/script.js');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>info add</title>
    <script><?= $script ?></script>
</head>
<body>
    <form id="infoForm" method="post">
        <label for="name">Имя: <input name="name" id="name" type="text" required></label>
        <label for="start">Начало: <input name="start" id="start" type="date" required></label>
        <label for="end">Конец: <input name="end" id="end" type="date" required></label>
        <label for="level">Выберите категорию:</label>
            <select id="level" name="levelid" required>
                <option value="">-- Выберите категорию --</option>
                <?= $options ?>
            </select>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>