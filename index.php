<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* @Setup */
if (!in_array('sessions', scandir('data'))) {
    mkdir('data/sessions');
    mkdir('data/migrations');
    mkdir('data/files');
    mkdir('data/temp');
}
/* @Setup */

require 'system/core.php';
require 'app/config.php';
require 'system/sessions_gc.php';
require 'system/autoloader.php';
if (DEBUG) {
    require 'system/migrator.php';
}
require 'system/AltoRouter.php';
require 'system/router.php';

session('alert', '');
mysqli_close($db);
