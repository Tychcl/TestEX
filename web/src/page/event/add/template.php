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
                    <label for="fio" class="form-label">ФИО</label>
                    <input 
                        name="teacher[fio]"
                        id="fio" 
                        type="text" 
                        class="form-input"
                        placeholder="Фамилия Имя Отчество">
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
                    <input type="file" name="teacher[file]" id="fileInput" onchange="updateButtonText(this)" required>
                </div>
            </div>
            
        </fieldset>
        <div id="students">
            <fieldset class="student">
                <p>Студент #1</p>
                <button class="delete-student" onclick="removeStudent(this)">Удалить</button>
                <div class="form-group">
                    <label class="form-label">ФИО</label>
                    <input 
                        name="student[0][fio]" 
                        type="text" 
                        class="form-input"
                        placeholder="Фамилия Имя Отчество" required>
                </div>
                <div class="form-group-mult">
                    <div class="form-group.mult">
                        <label for="role" class="form-label">Степень награды</label>
                        <select
                            name="student[0][award]"
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
                        <input type="file" name="student[0][file]" id="fileInput" onchange="updateButtonText(this)" required>
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