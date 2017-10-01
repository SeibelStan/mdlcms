<?php

class HelpersController extends BaseController {

    public static function friendlyUrl() {
        die(friendlyUrl(request('url')));
    }

    public static function setReflink($reflink) {
        session('reflink', $reflink);
        redirect('/');
    }

    public static function setLang($lang) {
        session('lang', $lang);
        back();
    }

    public static function markdownParse() {
        $postData = [
            'text' => request('data'),
            'mode' => 'markdown'
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_URL => 'https://api.github.com/markdown',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => ['Content-Type:application/json'],
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $result = curl_exec($ch);
        echo $result;
    }

}