<fieldset class="form-fieldset">
    <p>Экспорт файлов</p>
    <form id="export-form" class="form" method="POST">
        <div class="form-group-mult">
            <div class="form-group">
                <label for="start" class="form-label">Начало периода</label>
                <input 
                    name="start"
                    id="start" 
                    type="date" 
                    class="form-input"
                    placeholder="Дата начала"
                    required>
            </div>
            <div class="form-group">
                <label for="end" class="form-label">Конец периода</label>
                <input 
                    name="end"
                    id="end" 
                    type="date" 
                    class="form-input"
                    placeholder="Дата окончания"
                    required>
            </div>
            <div class="form-group" style="align-content: flex-end;">
                <button type="submit" class="form-button">
                    <span class="button-text">Экспорт</span>
                    <span class="button-loader" style="display: none;">⏳</span>
                </button>
            </div>
        </div>
    </form>
</fieldset>