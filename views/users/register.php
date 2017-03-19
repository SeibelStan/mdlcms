<?php include(view('includes/header')) ?>

<form class="ajax-form autolabel" action="<?= ROOT ?>users/do-register" method="post">
    <div class="form-group">
        <label>Логин</label>
        <input type="text" name="login" required>
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <input type="text" name="password" required>
    </div>
    <button type="submit">Создать</button>
</form>

<?php include(view('includes/footer')) ?>