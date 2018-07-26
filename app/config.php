<?php

setlocale(LC_ALL, 'ru_RU.UTF-8');
define('SITE_NAME', 'MDLCMS');
define('CURRENCY', 'KZT');

define('DOMAIN', $_SERVER['HTTP_HOST']);
define('USER_IP', $_SERVER['REMOTE_ADDR']);

$KEYS = pipeObj(file_get_contents('app/keys.txt'), '=');
define('CRONKEY', $KEYS->cron);
define('YANDEXKEY', $KEYS->yandex);

/* @BOT
define('BOTTOKEN', $KEYS->telegram);
define('BOTAPI', 'https://api.telegram.org/bot' . BOTTOKEN . '/');
$BOT = new Telegram(BOTTOKEN);
*/

if(preg_match('/\./', DOMAIN)) {
    define('ENV', 'prod');
}
else {
    define('ENV', 'local');
}
require('app/env-' . ENV . '.php');

define('FULLHOST', (SSL_ENABLED ? 'https' : 'http') . '://' . DOMAIN);

ini_set('session.gc_maxlifetime', SESSION_TIME);
ini_set('session.cookie_lifetime', SESSION_TIME);
ini_set('session.save_path', SESSION_PATH);
session_start();

define('USERID', session('user_id') ?: 0);

session('lang', getLang() != '' ? getLang() : getBrowserLang('ru'));
session('js', getJS() == '' ? 1 : getJS());