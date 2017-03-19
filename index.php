<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('system/core.php');
require('config.php');
require('system/autoloader.php');
if(DEBUG) {
    require('system/migrator.php');
}
require('vendor/AltoRouter.php');
require('system/router.php');
session('message', '');
session('message-type', '');