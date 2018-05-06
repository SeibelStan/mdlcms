<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('system/core.php');
require('vendor/telegram/Telegram.php');
require('vendor/telegram/TelegramMethods.php');
require('app/config.php');

$ERRORS = '';
if(!DEBUG) {
    set_error_handler('errorHandler');
    set_exception_handler('errorHandler');
}

require('system/sessions_gc.php');
require('system/autoloader.php');
if(DEBUG) {
    require('system/migrator.php');
}
require('vendor/AltoRouter.php');
require('system/router.php');
session('alert-message', '');
session('alert-type', '');

if(!DEBUG && MAILS && $ERRORS) {
    $logFile = 'data/errors.txt';
    if($ERRORS != file_get_contents($logFile)) {
        $headers = "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . SITE_NAME . "<" . NOTIFY_EMAIL . ">\r\n";
        mail(EMAIL_CONTACT, 'Error', $ERRORS, $headers);
    }
    file_put_contents($logFile, $ERRORS);
}