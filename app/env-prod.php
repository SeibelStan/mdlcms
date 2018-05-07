<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL_ENABLED', 0);
define('DEBUG', 0);
define('ATTEMPTS', 1);

define('MAILS', 1);
define('EMAIL_CONTACT', 'info@' . DOMAIN);

define('BOT', 0);
define('BOTMYID', 53540040);

define('DB_TIME_DELTA', 0);

$db = new mysqli('localhost', 'root', $KEYS->db_prod_pass);
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");

define('SESSION_PATH', 'data/sessions');
define('SESSION_TIME', 3*24*60*60);