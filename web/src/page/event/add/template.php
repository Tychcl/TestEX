<link rel="stylesheet" href="_files/_styles/championship.css">
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Участие в чемпионате</h1>
        <p class="form-subtitle">Введите данные, ФИО преподавателя может быть пустым</p>
    </div>
    <form id="form" class="form" method="POST">
        <div class="form-group">
            <label for="info" class="form-label">Чемпионат</label>
            <select
                name="info"
                id="info" 
                class="form-input"
                required>
                <option value="">Выберите</option>
                <?= $infos ?>
            </select>
        </div>
        <fieldset class="form-fieldset">
            <p>Преподаватель</p>
            <div class="form-group">
                <label for="teacher" class="form-label">ФИО</label>
                <select
                    name="teacher[id]"
                    id="tid" 
                    class="form-input">
                    <option value="">Выберите</option>
                    <?= $teachers ?>
                </select>
                    <!--<label for="fio" class="form-label">ФИО</label>
                    <input 
                        name="teacher[fio]"
                        id="tfio" 
                        type="text" 
                        class="form-input"
                        placeholder="Фамилия Имя Отчество"
                        pattern="[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*">-->
            </div>
            <div class="form-group-mult">
                <div class="form-group.mult">
                <label for="role" class="form-label">Роль участия</label>
                <select
                    name="teacher[role]"
                    id="role" 
                    class="form-input"
                    required>
                    <option value="">Выберите</option>
                    <?= $roles ?>
                </select>
                </div>
                <div class="file-input-wrapper">
                    <label for="fileInput" class="form-label">Документ</label>
                    <button class="file-input-button">Выберите файл</button>
                    <input type="file" name="teacher[file]" id="file" class="form-input" onchange="updateButtonText(this)" required accept=".pdf,.doc,.docx">
                </div>
            </div>
            
        </fieldset>
        <div id="students">
            <fieldset class="student">
                <div class="label">
                    <p>Студент #1</p>
                    <img src="_files/_images/_forms/close.svg" onclick="removeStudent(this)">
                </div>
                <div class="form-group">
                    <label class="form-label">ФИО</label>
                    <input 
                        id="fio"
                        name="students[0][fio]" 
                        type="text" 
                        class="form-input"
                        placeholder="Фамилия Имя Отчество" required
                        pattern="[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*">
                </div>
                <div class="form-group-mult">
                    <div class="form-group.mult">
                        <label for="role" class="form-label">Степень награды</label>
                        <select
                            name="students[0][award]"
                            id="role" 
                            class="form-input"
                            required>
                            <option value="">Выберите</option>
                            <?= $awards ?>
                        </select>
                    </div>
                    <div class="file-input-wrapper">
                        <label class="form-label">Документ</label>
                        <button class="file-input-button">Выберите файл</button>
                        <input type="file" name="students[0][file]" id="file" class="form-input" onchange="updateButtonText(this)" required accept=".pdf,.doc,.docx">
                    </div>
                </div>
            </fieldset>
        </div>
        <button type="button" onclick="addStudent()">+ Добавить студента</button>
        <button type="submit" class="form-button">
            <span class="button-text">Отправить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>