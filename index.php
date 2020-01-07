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

if (EHANDLER) {
    $ERRORS = '';
    if (!DEBUG) {
        set_error_handler('errorHandler');
        set_exception_handler('errorHandler');
    }
}

require 'system/sessions_gc.php';
require 'system/autoloader.php';
if (DEBUG) {
    require 'system/migrator.php';
}
require 'system/AltoRouter.php';
require 'system/router.php';

session('alert', '');
mysqli_close($db);

if (EHANDLER) {
    if (!DEBUG && MAILS && $ERRORS) {
        $logFile = 'data/temp/errors.txt';
        if ($ERRORS != file_get_contents($logFile)) {
            $headers = "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: " . SITE_NAME . "<" . NOTIFY_EMAIL . ">\r\n";
            mail(EMAIL_CONTACT, 'Error', $ERRORS, $headers);
        }
        file_put_contents($logFile, $ERRORS);
    }
}