<?php

class CartController extends BaseController {

    public static function get() {
        $model = new Cart(USERID ?: session_id());
        $result = $model->get();
        die(json_encode($result));
    }

    public static function add() {
        $model = new Cart(USERID ?: session_id());
        $result = $model->add($_REQUEST);
        die(json_encode($result));
    }

    public static function remove() {
        $model = new Cart(USERID ?: session_id());
        $model->deleteUnit('id', request('id'), "user_id = '$model->userId'");
        $result = [
            'callback' => 'getCart()'
        ];
        die(json_encode($result));
    }

}