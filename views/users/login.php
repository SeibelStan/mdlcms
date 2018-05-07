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
        <p class="mt-4"><a href="<?= ROOT ?>users/remind"><?= tr('pass_remind') ?></a></p>
    </div>
</main>

<?php include(view('includes/footer')) ?>