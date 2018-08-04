<?php if(getJS()) : ?>
<script>
    let ajaxButtons = document.querySelector('.form-ajax button');
    if(ajaxButtons) {
        [].forEach.call(ajaxButtons, function (el) {
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
<script>var alert = '<?= session('alert') ?>';</script>
<?php endif; ?>

</body>
</html>