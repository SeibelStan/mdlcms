<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/mdlcms/');
define('SSL_ENABLED', false);
define('DB_TIME_DELTA', 0);
define('DEBUG', false);
define('ATTEMPTS', true);
define('EMAIL_CONTACT', 'info@' . SITE_HOST);

$db = new mysqli('localhost', 'root', 'sss');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");
ini_set('session.save_path', 'data/sessions');