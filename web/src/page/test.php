<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <script src="_files/_scripts/championship.js"></script>
    <title>Регистрация участников</title>
</head>
<body>
    <form class="cham-form" id="cham-form" method="post" enctype="multipart/form-data">
        <!-- Данные преподавателя -->
        <fieldset>
            <legend>Данные преподавателя</legend>
            <label>ФИО преподавателя: <input type="text" name="teacher[fio]" required></label><br>
            <label>Чемпионат:
                <select name="teacher[championship_id]" required>
                    <option value="">-- Выберите чемпионат --</option>
                    <option value="1">Чемпионат 1</option>
                    <option value="2">Чемпионат 2</option>
                </select>
            </label><br>
            <label>Роль:
                <select name="teacher[role_id]" required>
                    <option value="">-- Выберите роль --</option>
                    <option value="1">Научный руководитель</option>
                    <option value="2">Консультант</option>
                </select>
            </label><br>
            <label>Документ преподавателя: <input type="file" name="teacher_document" required></label>
        </fieldset>

        <!-- Данные студентов -->
        <fieldset id="students-container">
            <legend>Студенты</legend>
            <div class="student-block">
                <h4>Студент #1</h4>
                <label>ФИО студента: <input type="text" name="students[0][fio]" required></label>
                <label>Степень награды: 
                    <select name="students[0][award_degree]" required>
                        <option value="1">1 место</option>
                        <option value="2">2 место</option>
                        <option value="3">3 место</option>
                    </select>
                </label>
                <label>Документ студента: <input type="file" name="student_documents[0]" required></label>
                <button type="button" onclick="removeStudent(this)">Удалить</button>
            </div>
        </fieldset>

        <button type="button" onclick="addStudent()">+ Добавить студента</button>
        <br><br>
        <button type="submit" class="button">Отправить</button>

    </form>
</body>
</html>