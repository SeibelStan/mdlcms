<?php

class HelpersController extends BaseController {

    public static function resetAttemts($type = '') {
        $where = $type ? "where type = '$type'" : '';
        echo dbu("delete from attempts $where");
        back();
    }

    public static function friendlyUrl() {
        die(friendlyUrl(request('url')));
    }

    public static function setLang($lang) {
        session('lang', $lang);
        back();
    }

    public static function markdownParse() {
        $postData = [
            's' => request('data')
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://helloacm.com/api/markdown/',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $result = curl_exec($ch);
        die(json_decode($result));
    }

}