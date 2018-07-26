<?php

class Helpers extends A_BaseModel {

    public static function getUser($id = USERID) {
        $id = $id ?: session('user_id');

        $user = Users::getByField('id', $id);
        if(!$user) {
            $user = Users::getByField('login', $id);
        }
        return $user;
    }

    public static function checkRoles($data) {
        if(!user()) {
            return false;
        }
        return array_intersect(
            explode(',', user()->roles),
            explode(',', $data)
        );
    }

    public static function guardAuth() {
        if(!USERID) {
            abort(401);
        }
    }
    public static function guardRoles($data) {
        if(!USERID || !Helpers::checkRoles($data)) {
            abort(401);
        }
    }

    public static function checkAdminZone() {
        $result = false;
        if(preg_match('/admin/', $_SERVER['REQUEST_URI'])) {
            $result = true;
        }
        return $result;
    }

    public static function translate($data, $langFrom = 'ru', $langTo = 'de') {
        //https://tech.yandex.ru/translate/doc/dg/reference/translate-docpage/
        $data = urlencode($data);
        $result = file_get_contents("https://translate.yandex.net/api/v1.5/tr.json/translate?key=" . YANDEXKEY . "&text={$data}&lang={$langFrom}-{$langTo}");
        $result = json_decode($result);
        return $result->code == 200 ? $result->text[0] : $data;
    }


}