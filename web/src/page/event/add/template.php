<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Участие в чемпионате</h1>
        <p class="form-subtitle">Введите данные, ФИО может быть пустым</p>
    </div>
    <form id="form" class="form" method="POST">
        <div class="form-group-mult">
            <div class="form-group.mult">
                <label for="fio" class="form-label">ФИО</label>
                <input 
                    name="teacher[fio]"
                    id="fio" 
                    type="text" 
                    class="form-input"
                    placeholder="Фамилия Имя Отчество">
            </div>
            <div class="file-input-wrapper">
                <label for="fileInput" class="form-label">Документ</label>
                <button class="file-input-button" id="fileButtonText">Выберите файл</button>
                <input type="file" name="teacher[file]" id="fileInput" onchange="updateButtonText(this)" required>
                <script>
                    function updateButtonText(input) {
                        const button = document.getElementById('fileButtonText');
                        if (input.files.length > 0) {
                            button.textContent = input.files[0].name;
                        } else {
                            button.textContent = 'Выберите файл';
                        }
                    }
                </script>
            </div>
        </div>
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
        <div class="form-group">
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
        <button type="submit" class="form-button">
            <span class="button-text">Добавить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>