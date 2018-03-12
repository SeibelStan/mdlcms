<?php

setlocale(LC_ALL, 'ru_RU.UTF-8');
define('SITE_NAME', 'MDLCMS');
define('SITE_HOST', $_SERVER['HTTP_HOST']);
define('EMAIL_ADMIN', 'admin@' . SITE_HOST);
define('USER_IP', $_SERVER['REMOTE_ADDR']);
define('CURRENCY', 'KZT');
define('CRONKEY', 'cron-key');
define('YANDEXKEY', 'trnsl.1.1.20170623T123335Z.781b890cb1136c8f.63af157d7ddb58d288c4378d40999ddabaa54c12');

if(preg_match('/\./', SITE_HOST)) {
    define('ENV', 'prod');
}
else {
    define('ENV', 'local');
}
require('app/env-' . ENV . '.php');

define('SITE_DOMAIN', (SSL_ENABLED ? 'https' : 'http') . '://' . SITE_HOST);

if(@$_SERVER['HTTP_USER_AGENT'] && preg_match('/curl/i', $_SERVER['HTTP_USER_AGENT'])) {
    ini_set('session.gc_maxlifetime', SESSION_TIME);
    ini_set('session.cookie_lifetime', SESSION_TIME);
}
ini_set('session.save_path', SESSION_PATH);
session_start();

define('USERID', session('user_id') ?: 0);

session('lang', getLang() != '' ? getLang() : getBrowserLang('ru'));
session('js', getJS() == '' ? 1 : getJS());