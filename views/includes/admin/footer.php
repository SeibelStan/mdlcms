<script src="<?= ROOT ?>vendor/jquery-3.3.1.min.js"></script>
<script src="//cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
<script src="<?= ROOT ?>vendor/jquery.generate-url.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/admin.js<?= assetTime() ?>"></script>
<input type="hidden" id="alert-message" value="<?= session('alert-message') ?>">
<input type="hidden" id="alert-type" value="<?= session('alert-type') ?>">

</body>
</html>