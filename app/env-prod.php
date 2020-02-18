<?php

ini_set('date.timezone', 'Asia/Almaty');

define('ROOT', '/');
define('SSL', 0);
define('DEBUG', 0);
define('EHANDLER', 0);
define('ATTEMPTS', 1);

define('MAILS', 1);
define('EMAIL_CONTACT', 'info@' . DOMAIN);

define('DB_TIME_DELTA', 0);

$db = new mysqli($KEYS->db_prod_host, $KEYS->db_prod_user, $KEYS->db_prod_pass);
$db->select_db($KEYS->db_prod);
$db->query("SET NAMES utf8");

define('SESSION_PATH', 'data/sessions');
define('SESSION_TIME', 1*1*60*60);

$guardCounts = [
    'view'     => [500, 600],
    'login'    => [3, 10],
    'register' => [1, 1],
    'remind'   => [2, 3],
    'feedback' => [1, 2],
];