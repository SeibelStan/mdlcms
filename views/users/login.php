<?php include(view('includes/header')) ?>

<main class="container">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <form class="form-ajax form-login autolabel" action="<?= ROOT ?>users/do-login" method="post">
            <div class="form-group">
                <label><?= tr('login_or_email') ?></label>
                <input class="form-control" type="text" name="login" required>
            </div>
            <div class="form-group">
                <label><?= tr('password') ?></label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <button class="btn btn-primary" type="submit"><?= tr('log_in') ?></button>
        </form>
        <form class="form-ajax form-remind mt-3" action="<?= ROOT ?>users/remind" method="post">
            <input type="hidden" name="login" required>
            <button class="btn btn-link" type="submit"><?= tr('forgot_password') ?></button>
        </form>
    </div>
</main>

<?php include(view('includes/footer')) ?>