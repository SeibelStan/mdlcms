<?php
    global $pageTitle;
?>
<!DOCTYPE html>
<html lang="<?= getLang() ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= SITE_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= ROOT ?>assets/img/favicon.png">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/inscrybmde/inscrybmde.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/app.css<?= assetTime() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/admin.css<?= assetTime() ?>">
    <script>
        var ROOT = '<?= ROOT ?>';
        var FULLHOST = '<?= FULLHOST ?>';
    </script>
</head>
<body>

<header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarCollapse" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= ROOT ?>admin">Управление</a>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="<?= ROOT ?>admin/edit-models">Модели</a>
            <li class="nav-item"><a class="nav-link" href="<?= ROOT ?>admin/files">Файлы</a>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <?= user()->login ?>
                </a>
                <span class="caret"></span>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= ROOT ?>">Сайт</a>
                    <li><a class="dropdown-item" href="<?= ROOT ?>users/logout">Выход</a>
                </ul>
            </li>
        </ul>
    </div>
</nav>
</header>

<?php if (!getJS() && session('alert')) : ?>
    <div class="alert alert-<?= alert()->type ?: 'danger' ?> alert-sticky nojs"><?= alert()->message ?></div>
<?php endif; ?>

<?= $viewContent ?>

<script>
    let ajaxButtons = document.querySelector('.form-ajax button');
    if (ajaxButtons) {
        [].forEach.call(ajaxButtons, function (el) {
            el.setAttribute('disabled', true);
        });
    }
</script>

<script src="<?= ROOT ?>vendor/jquery.min.js"></script>
<script src="<?= ROOT ?>vendor/inscrybmde/inscrybmde.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.generate-url.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>assets/js/core.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/admin.js<?= assetTime() ?>"></script>
<script>var alert = '<?= session('alert') ?>';</script>

</body>
</html>