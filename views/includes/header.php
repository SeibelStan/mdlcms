<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= SITE_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= ROOT ?>assets/img/favicon.png">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/slick/slick.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/slick/slick-theme.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/ekko-lightbox/ekko-lightbox.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/app.css<?= assetTime() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/site.css<?= assetTime() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/adaptive.css<?= assetTime() ?>">
    <script src="<?= ROOT ?>vendor/jquery-<?= JQUERY_VERSION ?>.min.js"></script>
    <meta name="theme-color" content="#ffffff">
    <script>
        var baseURL = '<?= ROOT ?>';
        var domainURL = '<?= SITE_DOMAIN ?>';
    </script>
    <?php $codeparts = Helpers::getCodeparts('site'); ?>
    <?php include(view('includes/codeparts')) ?>
</head>
<body>

<header>
<nav class="navbar navbar-toggleable-md bg-inverse navbar-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= ROOT ?>"><?= SITE_NAME ?></a>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <?php foreach(Helpers::getMenu('main') as $item) : ?>
                <li class="nav-item">
                    <a class="nav-link <?= $item->active ? 'active' : '' ?>"
                        <?= $item->params ?> href="<?= $item->external ? '' : ROOT ?><?= $item->link ?>">
                        <?= $item->title ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <form id="search-form" class="form-inline my-2 my-lg-0 mr-auto dropdown" action="<?= ROOT ?>search" method="post">
            <input class="form-control mr-sm-2 search-widget-trigger" placeholder="Поиск"
                required data-toggle="dropdown" type="text" name="searchQuery" value="<?= request('searchQuery') ?>">
            <button class="btn my-2 my-sm-0" type="submit">Искать</button>
            <ul class="dropdown-menu search-widget">
                <li class="dropdown-item text-muted">Напишите запрос
            </ul>
        </form>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-dropdown" data-toggle="dropdown">
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
                <a class="nav-link dropdown-toggle user-dropdown" data-toggle="dropdown">
                    <?php if(USERID) : ?>
                        <?= Helpers::getUser()->login ?>
                    <?php else : ?>
                        Вход / Регистрация
                    <?php endif; ?>
                </a>
                <span class="caret"></span>
                <ul class="dropdown-menu">
                    <?php if(USERID) : ?>
                        <?php if(Helpers::checkRoles('admin')) : ?>
                            <li><a class="dropdown-item" href="<?= ROOT ?>admin/edit-models">Управление</a>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users">Профиль</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>orders">Заказы</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/logout">Выход</a>
                    <?php else : ?>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/register">Регистрация</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/login">Вход</a>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>
</nav>
</header>