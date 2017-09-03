<?php include(view('includes/header')) ?>

<main class="container">
    <form class="col-lg-4 col-md-6 col-sm-12 form-ajax autolabel" action="<?= ROOT ?>users/do-register" method="post">
        <div class="form-group">
            <label>Логин</label>
            <input class="form-control" type="text" name="login" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input class="form-control" type="password" name="password" required>
        </div>
        <input class="form-control" type="hidden" name="referal" value="<?= session('referal') ?>">
        <button class="btn btn-primary" type="submit">Создать</button>
    </form>
</main>

<?php include(view('includes/footer')) ?>