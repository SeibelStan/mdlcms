<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/mdlcms/');
define('DB_TIME_DELTA', 0);
define('DEBUG', true);
define('ATTEMPTS', false);
define('EMAIL_CONTACT', 'seibel.stan@ya.ru');

$db = new mysqli('localhost', 'root', 'sss');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");
ini_set('session.save_path', '/var/www/html' . ROOT . 'sessions');