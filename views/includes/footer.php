<?php if(getJS()) : ?>
    <script src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
    <script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="<?= ROOT ?>vendor/jquery.form.js"></script>
    <script src="<?= ROOT ?>vendor/ekko-lightbox/ekko-lightbox.js"></script>
    <script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
    <input type="hidden" id="currency" value="<?= CURRENCY ?>">
    <input type="hidden" id="alert-message" value="<?= session('alert-message') ?>">
    <input type="hidden" id="alert-type" value="<?= session('alert-type') ?>">
<?php endif; ?>

</body>
</html>