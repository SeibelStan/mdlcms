    <script src="//cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
    <script src="<?= ROOT ?>vendor/jquery.generate-url.js"></script>
    <script src="<?= ROOT ?>vendor/popper.min.js"></script>
    <script src="<?= ROOT ?>vendor/bootstrap/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>vendor/jquery.form.js"></script>
    <script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
    <script src="<?= ROOT ?>assets/js/admin.js<?= assetTime() ?>"></script>
    <input type="hidden" id="smes-val" value="<?= session('message') ?>">
    <input type="hidden" id="smes-type" value="<?= session('message-type') ?>">
</body>
</html>