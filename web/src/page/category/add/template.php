<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Учет категорий</h1>
        <p class="form-subtitle">Введите данные категории, ФИО преподавателя может быть пустым</p>
    </div>
    <form id="form" class="form" method="POST">
        <div class="form-group">
            <label for="teacher" class="form-label">ФИО</label>
            <select
                name="teacherid"
                id="tid" 
                class="form-input">
                <option value="">Выберите</option>
                <?= $teachers ?>
            </select>                    
        </div>
        <div class="form-group">
            <label class="form-label">Орган издавший приказ</label>
            <input 
                id="organ"
                name="organ" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="date" class="form-label">Дата</label>
            <input 
                name="date"
                id="date" 
                type="date" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="num" class="form-label">Номер приказа</label>
            <input 
                id="num"
                name="num" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="post" class="form-label">Должность</label>
            <input 
                id="post"
                name="post" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="place" class="form-label">Образовательная организация</label>
            <input 
                id="place"
                name="place" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="categoryid" class="form-label">Присвоенная категория</label>
            <select
                name="categoryid"
                id="categoryid" 
                class="form-input"
                required>
                <option value="">Выберите</option>
                <?= $categorys ?>
            </select>
        </div>
        <button type="submit" class="form-button">
            <span class="button-text">Отправить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>