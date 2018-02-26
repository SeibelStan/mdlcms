<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL_ENABLED', 0);
define('DB_TIME_DELTA', 0);
define('DEBUG', 1);
define('ATTEMPTS', 0);
define('MAILS', 0);
define('EMAIL_CONTACT', 'seibel.stan@ya.ru');

$db = new mysqli('localhost', 'root', 'sss');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");
ini_set('session.save_path', 'data/sessions');