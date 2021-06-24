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
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/app.css<?= assetTime() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/site.css<?= assetTime() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/adaptive.css<?= assetTime() ?>">
    <?php if (getJS()) : ?>
        <script>
            var ROOT = '<?= ROOT ?>';
            var FULLHOST = '<?= FULLHOST ?>';
        </script>
    <?php endif; ?>
</head>
<body>

<header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarCollapse" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= ROOT ?>"><?= SITE_NAME ?></a>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="<?= ROOT ?>catalog">Каталог</a>
            <li class="nav-item"><a class="nav-link" href="http://github.com/Seibelstan/mdlcms">GitHub</a>
            
        </ul>

        <form id="search-form" class="form-inline my-2 my-lg-0 me-auto dropdown" action="<?= ROOT ?>search" method="post">
            <input class="form-control me-sm-2 search-widget-trigger" placeholder="Поиск"
                required data-bs-toggle="dropdown" type="text" name="searchQuery" value="<?= request('searchQuery') ?>">
            <button class="btn my-2 my-sm-0" type="submit">Искать</button>
            <ul class="dropdown-menu search-widget">
                <li class="dropdown-item text-muted">Напишите запрос
            </ul>
        </form>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    Корзина
                </a>
                <span class="caret"></span>
                <ul id="cart-widget" class="dropdown-menu items-nohover">
                    <li class="dropdown-item noremove" id="cart-widget-actions">
                        <form class="form-ajax" action="<?= ROOT ?>orders/create" method="post">
                            <button class="btn btn-success btn-sm">Купить (<span class="cart-sum"></span> <?= CURRENCY ?>)</button>
                        </form>
                    <li class="dropdown-item noremove text-muted" id="cart-widget-noitems">Корзина пуста
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-dropdown" data-bs-toggle="dropdown">
                    <?php if (USERID) : ?>
                        <?= user()->login ?>
                    <?php else : ?>
                        Вход / Регистрация
                    <?php endif; ?>
                </a>
                <span class="caret"></span>
                <ul class="dropdown-menu">
                    <?php if (USERID) : ?>
                        <?php if (Helpers::checkRoles('admin')) : ?>
                            <li><a class="dropdown-item" href="<?= ROOT ?>admin/edit-models">Управление</a>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users">Профиль</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>orders">Заказы</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/logout">Выход</a>
                    <?php else : ?>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/login">Вход</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/register">Регистрация</a>
                    <?php endif; ?>
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

<?php if (getJS()) : ?>
<script>
    let ajaxButtons = document.querySelector('.form-ajax button');
    if (ajaxButtons) {
        [].forEach.call(ajaxButtons, function (el) {
            el.setAttribute('disabled', true);
        });
    }
</script>

<script src="<?= ROOT ?>vendor/jquery.min.js"></script>
<script src="<?= ROOT ?>vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= ROOT ?>vendor/jquery.form.js"></script>
<script src="<?= ROOT ?>assets/js/core.js<?= assetTime() ?>"></script>
<script src="<?= ROOT ?>assets/js/app.js<?= assetTime() ?>"></script>
<input type="hidden" id="currency" value="<?= CURRENCY ?>">
<script>var alert = '<?= session('alert') ?>';</script>
<?php endif; ?>

</body>
</html>