<?php

ini_set('date.timezone', 'Asia/Almaty');
define('ROOT', '/mdlcms/');
define('DB_TIME_DELTA', 0);
$db = new mysqli('localhost', 'root', 'sss');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");