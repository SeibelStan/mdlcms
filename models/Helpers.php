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
        $model = new Codeparts($namespace ? "namespace = '$namespace'" : '');
        return $model->getUnits();
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

}