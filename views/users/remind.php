<main class="container page-inner">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <form class="form-ajax form-remind mt-3" action="<?= ROOT ?>users/do-remind" method="post">
            <div class="form-group">
                <label><?= tr('login_or_email') ?></label>
                <input class="form-control" type="text" name="login" required>
            </div>
            <button class="btn btn-primary" type="submit"><?= tr('pass_remind') ?></button>
        </form>
    </div>
</main>