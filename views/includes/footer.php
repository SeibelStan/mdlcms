<script src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
<script src="<?= ROOT ?>vendor/popper.min.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>vendor/ekko-lightbox/ekko-lightbox.js"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<input type="hidden" id="currency" value="<?= CURRENCY ?>">
<input type="hidden" id="smes-val" value="<?= session('message') ?>">
<input type="hidden" id="smes-type" value="<?= session('message-type') ?>">

</body>
</html>