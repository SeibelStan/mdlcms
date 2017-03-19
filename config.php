<?php

define('SITE_NAME', 'MDLCMS');

define('SITE_HOST', $_SERVER['HTTP_HOST']);
define('SITE_DOMAIN', 'http://' . SITE_HOST);
define('ADMIN_EMAIL', 'admin@' . SITE_HOST);
define('CONTACT_EMAIL', 'seibel.stan@ya.ru');
define('USER_IP', $_SERVER['REMOTE_ADDR']);
define('DEBUG', true);
define('ATTEMPTS', true);

if(preg_match('/\./', SITE_HOST)) {
    define('ENV', 'prod');
}
else {
    define('ENV', 'local');
}
require('env-' . ENV . '.php');

if(ATTEMPTS) {
    if(dbs("select * from banned_ip where ip = '" . USER_IP . "'")) {
        abort(402);
    }
}

if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != 'CURL') {
	ini_set('session.gc_maxlifetime', 604800);
	ini_set('session.cookie_lifetime', 604800);
}
session_start();
define('USERID', session('user_id'));