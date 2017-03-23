    <script src="<?= ROOT ?>vendor/jquery-3.1.1.min.js"></script>
    <script src="<?= ROOT ?>vendor/tether.min.js"></script>
    <script src="<?= ROOT ?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>vendor/jquery.form.js"></script>
    <script src="<?= ROOT ?>vendor/slick/slick.min.js"></script>
    <script src="<?= ROOT ?>vendor/ekko-lightbox/ekko-lightbox.js"></script>
    <script src="<?= ROOT ?>vendor/twitter.relative.time.min.js"></script>
    <script src="<?= ROOT ?>assets/js/app.js"></script>
    <input type="hidden" id="currency" value="<?= CURRENCY ?>">
    <input type="hidden" id="smes-val" value="<?= session('message') ?>">
    <input type="hidden" id="smes-type" value="<?= session('message-type') ?>">
</body>
</html>