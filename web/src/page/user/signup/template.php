<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Добавление чемпионата</h1>
        <p class="form-subtitle">Введите данные чемпионата, дату окончания можно не вводить, если чемпионат заканчивается в тот же день</p>
    </div>
    <form id="form" class="form" method="POST">
        <div class="form-group">
            <label class="form-label">ФИО</label>
            <input 
                id="fio"
                name="fio" 
                type="text" 
                class="form-input"
                placeholder="Фамилия Имя Отчество" required
                pattern="[А-Я]{1}[a-я]* [А-Я]{1}[a-я]* [А-Я]{1}[a-я]*">
        </div>
        <div class="form-group">
            <label class="form-label">Логин</label>
            <input 
                id="login"
                name="login" 
                type="text" 
                class="form-input"
                placeholder="Логин" required>
        </div>
        <div class="form-group">
            <label class="form-label">Пароль</label>
            <input 
                id="password"
                name="password" 
                type="text" 
                class="form-input"
                placeholder="Пароль" required>
        </div>
        <div class="form-group">
            <label class="form-label">Уровень доступа</label>
            <select
                name="roleid"
                id="role" 
                class="form-input"
                required>
                <option value="">Выберите</option>
                <?= $roles ?>
            </select>
        </div>
        <button type="submit" class="form-button">
            <span class="button-text">Отправить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>