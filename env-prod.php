<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('DB_TIME_DELTA', 6 * 60 * 60);
define('DEBUG', false);
define('ATTEMPTS', true);
define('EMAIL_CONTACT', 'info@' . SITE_HOST);

$db = new mysqli('localhost', 'prod_root', 'prod_pass');
$db->select_db('mdlcms' . $dbPostfix);
$db->query("SET NAMES utf8");
//ini_set('session.save_path', '/path/to/save');