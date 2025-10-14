<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Учет квалификаций</h1>
        <p class="form-subtitle">Введите данные категории, ФИО преподавателя может быть пустым<br>Если ФИО пустое, то учет присвоется вам</p>
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
            <label for="num" class="form-label">Номер удостоверения</label>
            <input 
                id="num"
                name="num" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="regnum" class="form-label">Регистрационный номер</label>
            <input 
                id="regnum"
                name="regnum" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label class="form-label">Город</label>
            <input 
                id="city"
                name="city" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="date" class="form-label">Дата выдачи</label>
            <input 
                name="date"
                id="date" 
                type="date" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="place" class="form-label">Место прохождения</label>
            <input 
                id="place"
                name="place" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group-mult">
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
                    placeholder="Дата окончания">
            </div>
        </div>
        <div class="form-group">
            <label for="theme" class="form-label">Тема</label>
            <input 
                id="theme"
                name="theme" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="hours" class="form-label">Объем в часах</label>
            <input 
                id="hours"
                name="hours" 
                type="number" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="director" class="form-label">Руководитель/Директор</label>
            <input 
                id="director"
                name="director" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <label for="secretary" class="form-label">Секретарь</label>
            <input 
                id="secretary"
                name="secretary" 
                type="text" 
                class="form-input"
                required>
        </div>
        <div class="form-group">
            <div class="file-input-wrapper">
                <label class="form-label">Документ</label>
                <button class="file-input-button">Выберите файл</button>
                <input type="file" name="document" id="file" class="form-input" onchange="updateButtonText(this)" required accept=".pdf,.doc,.docx">
            </div>
        </div>
        <button type="submit" class="form-button">
            <span class="button-text">Отправить</span>
            <span class="button-loader" style="display: none;">⏳</span>
        </button>
    </form>
</div>