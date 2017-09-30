<?php include(view('includes/header')) ?>

<main class="container">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <form class="form-ajax form-login autolabel" action="<?= ROOT ?>users/do-login" method="post">
            <div class="form-group">
                <label>Логин или почта</label>
                <input class="form-control" type="text" name="login" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <button class="btn btn-primary" type="submit">Войти</button>
        </form>
        <form class="form-ajax form-remind mt-3" action="<?= ROOT ?>users/remind" method="post">
            <input type="hidden" name="login" required>
            <button class="btn btn-link" type="submit">Забыли пароль?</button>
        </form>
    </div>
</main>

<?php include(view('includes/footer')) ?>