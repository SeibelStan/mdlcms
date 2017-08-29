<?php

class Helpers extends A_BaseModel {

    public static function getMenu($namespace) {
        $model = new Menu();
        $items = $model->getUnits("namespace = '$namespace'", "sort asc, title asc");
        foreach($items as $item) {
            $linkPreg = preg_replace('/\//', '\/', ROOT . $item->link);
            $item->active = preg_match('/' . $linkPreg . '/', $_SERVER['REQUEST_URI']);
        }
        return $items;
    }

    public static function getCodeparts($namespace = false) {
        $model = new Codeparts();
        return $model->getUnits($namespace ? "namespace = '$namespace'" : '');
    }

    public static function getUser($id = USERID) {
        $id = USERID ?: session('user_id');
        $authUser = new Users;
        return $authUser->getByField('id', $id);
    }

    public static function checkRoles($data) {
        if(!Helpers::getUser()) {
            return false;
        }
        return array_intersect(
            explode(',', Helpers::getUser()->roles),
            explode(',', $data)
        );
    }

    public static function translate($data, $langFrom = 'ru', $langTo = 'de') {
        //https://tech.yandex.ru/translate/doc/dg/reference/translate-docpage/
        $result = file_get_contents("https://translate.yandex.net/api/v1.5/tr.json/translate?key=" . YANDEXKEY . "&text={$data}&lang={$langFrom}-{$langTo}");
        $result = json_decode($result);
        return $result->code == 200 ? $result->text[0] : $data;
    }

}