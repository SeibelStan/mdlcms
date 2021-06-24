<main class="container">
    <form class="col-lg-4 col-md-6 col-sm-12 form-ajax autolabel" action="<?= ROOT ?>users/do-register" method="post">
        <div class="mb-3">
            <label><?= tr('login') ?></label>
            <input class="form-control" type="text" name="login" required>
        </div>
        <div class="mb-3">
            <label><?= tr('password') ?></label>
            <input class="form-control" type="password" name="password" required>
        </div>
        <input class="form-control" type="hidden" name="reflink" value="<?= session('reflink') ?>">
        <button class="btn btn-primary" type="submit"><?= tr('sign_up') ?></button>
    </form>
</main>