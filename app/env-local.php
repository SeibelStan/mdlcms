<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL', 0);
define('DEBUG', 1);
define('EHANDLER', 0);
define('ATTEMPTS', 0);

define('MAILS', 0);
define('EMAIL_CONTACT', 'seibel.stan@ya.ru');

define('DB_TIME_DELTA', 0);

$db = new mysqli($KEYS->db_local_host, $KEYS->db_local_user, $KEYS->db_local_pass);
$db->select_db($KEYS->db_local);
$db->query("SET NAMES utf8");

define('SESSION_PATH', 'data/sessions');
define('SESSION_TIME', 1*24*60*60);

$guardCounts = [
    'view'     => [500, 600],
    'login'    => [5, 10],
    'register' => [1, 10],
    'remind'   => [2, 10],
    'feedback' => [1, 10],
];