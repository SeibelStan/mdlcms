<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL_ENABLED', 0);
define('DB_TIME_DELTA', 0);
define('DEBUG', 0);
define('ATTEMPTS', 1);
define('MAILS', 1);
define('EMAIL_CONTACT', 'info@' . SITE_HOST);

$db = new mysqli('localhost', 'root', 'sss');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");
ini_set('session.save_path', 'data/sessions');