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

    public static function getStateText($state) {
        switch($state) {
            case -2 : return 'Возврат';
            case -1 : return 'Отменён';
            case  0 : return 'В обработке';
            case  1 : return 'Принят';
            case  2 : return 'Передан доставке';
            case  3 : return 'Доставлен';
            case  5 : return 'Завершен';
        }
    }

    public static function getUser($id = USERID) {
        $authUser = new Users;
        return $authUser->getByField('id', $id);
    }

}