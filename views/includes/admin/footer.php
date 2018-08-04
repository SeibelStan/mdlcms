<script>
    let ajaxButtons = document.querySelector('.form-ajax button');
    if(ajaxButtons) {
        [].forEach.call(ajaxButtons, function (el) {
            el.setAttribute('disabled', true);
        });
    }
</script>

<script src="<?= ROOT ?>vendor/jquery-3.3.1.min.js"></script>
<script src="<?= ROOT ?>vendor/ckeditor.js"></script>
<script src="<?= ROOT ?>vendor/jquery.generate-url.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>assets/js/core.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/admin.js<?= assetTime() ?>"></script>
<script>var alert = JSON.parse('<?= session('alert') ?>');</script>

</body>
</html>