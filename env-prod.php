<?php

ini_set('date.timezone', 'Asia/Almaty');
define('ROOT', '/');
define('DB_TIME_DELTA', 6 * 60 * 60);
$db = new mysqli('localhost', 'prod_root', 'prod_pass');
$db->select_db('mdlcms');
$db->query("SET NAMES utf8");
//ini_set('session.save_path', '/path/to/save');