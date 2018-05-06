<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL_ENABLED', 0);
define('DEBUG', 1);
define('ATTEMPTS', 0);

define('MAILS', 0);
define('EMAIL_CONTACT', 'seibel.stan@ya.ru');

define('BOT', 0);
define('BOTMYID', 0);

define('DB_TIME_DELTA', 0);

$db = new mysqli('localhost', 'debian-sys-maint', $KEYS->db_local_pass);
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");

define('SESSION_PATH', 'data/sessions');
define('SESSION_TIME', 1*24*60*60);