<link rel="stylesheet" href="_files/_styles/championship.css">
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
    <fieldset class="student">
        <div class="label">
            <p>Категория: <?= $cat?></p>
            <img src="_files/_images/_forms/openac.svg" onclick="showhide(this)">
        </div>
        <div id="hide" class="content"><?= $files ?? 'Нет файлов'?></div>
    </fieldset>
    <fieldset class="student">
        <div class="label">
            <p>Квалификация: <?= $skill?></p>
            <img src="_files/_images/_forms/openac.svg" onclick="showhide(this)">
        </div>
        <div id="hide" class="content"><?= $files ?? 'Нет файлов'?></div>
    </fieldset>
    <?= $export?>
</div>