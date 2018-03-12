<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('system/core.php');
require('app/config.php');
require('system/sessions_gc.php');
require('system/autoloader.php');
if(DEBUG) {
    require('system/migrator.php');
}
require('vendor/AltoRouter.php');
require('system/router.php');
session('alert-message', '');
session('alert-type', '');