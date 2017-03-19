<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_NAME ?><?= isset($pageTitle) ? ' - ' . $pageTitle : '' ?></title>
    <link rel="icon" type="image/png" href="<?= ROOT ?>assets/img/favicon.png">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/app.css">
    <meta name="theme-color" content="#ffffff">
    <script src="<?= ROOT ?>vendor/jquery-3.1.1.min.js"></script>
    <script>
        var baseURL = '<?= ROOT ?>';
        var domainURL = '<?= SITE_DOMAIN ?>';
    </script>
</head>
<body>