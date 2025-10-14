<fieldset class="form-fieldset">
    <p>Экспорт файлов</p>
    <form id="form" class="form" method="POST">
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
                    placeholder="Дата окончания"
                    required>
            </div>
        </div>
    </form>
</fieldset>