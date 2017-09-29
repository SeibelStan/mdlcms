    <script src="<?= ROOT ?>vendor/ckeditor/ckeditor.js?v=<?= time() ?>"></script>
    <script src="<?= ROOT ?>vendor/ckeditor/adapters/jquery.js"></script>
    <script src="<?= ROOT ?>vendor/tether.min.js"></script>
    <script src="<?= ROOT ?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>vendor/jquery.form.js"></script>
    <script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
    <script src="<?= ROOT ?>assets/js/admin.js<?= assetTime() ?>"></script>
    <input type="hidden" id="smes-val" value="<?= session('message') ?>">
    <input type="hidden" id="smes-type" value="<?= session('message-type') ?>">
</body>
</html>