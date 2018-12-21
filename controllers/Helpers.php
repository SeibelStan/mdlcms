<?php

class HelpersController {

    public static function setReflink($reflink) {
        session('reflink', $reflink);
        redirect('/');
    }

    public static function setLang($lang) {
        session('lang', $lang);
        $location = @$_SERVER['HTTP_REFERER'] ?: ROOT;
        redirect(preg_replace('/-(en|ru)/', '-' . $lang, $location));
    }

    public static function setJS($lang) {
        session('js', $lang);
        back();
    }

}