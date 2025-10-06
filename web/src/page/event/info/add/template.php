<script src="<?= $script ?>"></script>
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Добавление чемпионата</h1>
        <p class="form-subtitle">Введите данные чемпионата</p>
    </div>
    <form id="form" class="auth-form" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Логин</label>
            <input 
                name="name"
                id="name" 
                type="text" 
                class="form-input"
                placeholder="Наименование"
                required>
        </div>
        <div class="form-group">
            <label for="start" class="form-label">Начало</label>
            <input 
                name="start"
                id="start" 
                type="date" 
                class="form-input"
                placeholder="Дата начала"
                required>
        </div>
        <div class="form-group">
            <label for="end" class="form-label">Конец</label>
            <input 
                name="end"
                id="end" 
                type="date" 
                class="form-input"
                placeholder="Дата окончания"
                required>
        </div>
        <div class="form-group">
            <label for="level" class="form-label">Уровень проведения</label>
            <select
                name="level"
                id="level" 
                class="form-input"
                required>
                <option value="">Выберите</option>
                <?= $options ?>
            </select>
        </div>
        <button type="submit" class="form-button">
            <span class="button-text">Добавить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>