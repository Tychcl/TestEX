<div class="form-container">
    <div class="form-header" style="margin-bottom: 15px;">
        <h1 class="form-title">Профиль <?= $role ?></h1>
    </div>
    <div class="form-group">
        <label class="form-label">ФИО</label>
        <input 
            id="ignore" 
            type="text" 
            class="form-input"
            value="<?= $fio?>"
            disabled>
    </div>
    <fieldset class="form-fieldset">
        <p>Квалификация: <?= $skill?></p>
    </fieldset>
    <fieldset class="form-fieldset">
        <p>Категория: <?= $cat?></p>
    </fieldset>
    <?= $export?>
</div>