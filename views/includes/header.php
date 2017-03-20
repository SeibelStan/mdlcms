<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_NAME ?><?= isset($pageTitle) ? ' - ' . $pageTitle : '' ?></title>
    <link rel="icon" type="image/png" href="<?= ROOT ?>assets/img/favicon.png">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/slick/slick.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/slick/slick-theme.css">
    <link rel="stylesheet" href="<?= ROOT ?>vendor/ekko-lightbox/ekko-lightbox.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/app.css">
    <script src="<?= ROOT ?>vendor/jquery-3.1.1.min.js"></script>
    <meta name="theme-color" content="#ffffff">
    <script>
        var baseURL = '<?= ROOT ?>';
        var domainURL = '<?= SITE_DOMAIN ?>';
    </script>
    <?php $codeparts = getCodeparts(); ?>
    <?php include(view('includes/codeparts')) ?>
</head>
<body>

<nav class="navbar navbar-toggleable-md bg-inverse navbar-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= ROOT ?>admin">MDLCMS</a>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <?php foreach(getMenu('main') as $item) : ?>
                <li class="nav-item">
                    <a class="nav-link" <?= $item->params ?> href="<?= $item->external ? '' : ROOT ?><?= $item->link ?>">
                        <?= $item->title ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-dropdown" data-toggle="dropdown">
                    Корзина
                </a>
                <span class="caret"></span>
                <ul class="dropdown-menu items-nohover">
                    <li class="dropdown-item">
                        <a href="<?= ROOT ?>">Товар первый (2) - 250 <?= CURRENCY ?></a>
                        <a class="pull-right">&times;</a>
                    <li class="dropdown-item">
                        <a href="<?= ROOT ?>">Товар второй - 7000 <?= CURRENCY ?></a>
                        <a class="pull-right">&times;</a>
                    <li class="dropdown-item"><button class="btn btn-success btn-sm">Купить</button>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-dropdown" data-toggle="dropdown">
                    <?php if(USERID) : ?>
                        <?= getUser()->login ?>
                    <?php else : ?>
                        Вход / Регистрация
                    <?php endif; ?>
                </a>
                <span class="caret"></span>
                <ul class="dropdown-menu">
                    <?php if(USERID) : ?>
                        <?php if(getUser()->isadmin) : ?>
                            <li class="dropdown-item"><a href="<?= ROOT ?>admin">Управление</a>
                        <?php endif; ?>
                        <li class="dropdown-item"><a href="<?= ROOT ?>users/logout">Выход</a>
                    <?php else : ?>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/register">Регистрация</a>
                        <li><a class="dropdown-item" href="<?= ROOT ?>users/login">Вход</a>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>
</nav>