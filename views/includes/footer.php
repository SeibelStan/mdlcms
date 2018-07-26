<?php if(getJS()) : ?>
<script>
    let ajaxButtons = document.querySelector('.form-ajax button');
    if(ajaxButtons) {
        ajaxButtons.forEach(function (el) {
            el.setAttribute('disabled', true);
        });
    }
</script>

<script src="<?= ROOT ?>vendor/jquery-3.3.1.min.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>assets/js/core.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<input type="hidden" id="currency" value="<?= CURRENCY ?>">
<input type="hidden" id="alert-message" value="<?= session('alert-message') ?>">
<input type="hidden" id="alert-type" value="<?= session('alert-type') ?>">
<?php endif; ?>

</body>
</html>