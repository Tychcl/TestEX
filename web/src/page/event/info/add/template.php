<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>info add</title>
    <script src="script.js"></script>
</head>
<body>
    <form id="form" method="post">
        <label for="name">Имя: <input name="name" id="name" type="text"></label>
        <label for="start">Начало: <input name="start" id="start" type="date"></label>
        <label for="end">Конец: <input name="end" id="end" type="date"></label>
        <label for="level">Выберите категорию:</label>
            <select id="level" name="levelid" required>
                <option value="">-- Выберите категорию --</option>
                <?= $options ?>
            </select>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>