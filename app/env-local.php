<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL', 0);
define('DEBUG', 1);
define('EHANDLER', 0);
define('ATTEMPTS', 0);

define('MAILS', 0);
define('EMAIL_CONTACT', 'seibel.stan@ya.ru');

/* @Bot
define('BOT', 0);
define('BOTMYID', 0);
/* /Bot */

define('DB_TIME_DELTA', 0);

$db = new mysqli($KEYS->db_local_host, $KEYS->db_local_user, $KEYS->db_local_pass);
$db->select_db($KEYS->db_local);
$db->query("SET NAMES utf8");

define('SESSION_PATH', 'data/sessions');
define('SESSION_TIME', 1*24*60*60);