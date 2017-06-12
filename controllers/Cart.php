<?php

class CartController extends BaseController {

    public static function get() {
        $model = new Cart();
        $result = $model->get();
        echo json_encode($result);
    }

    public static function add() {
        $model = new Cart();
        $result = $model->add($_REQUEST);
        echo json_encode($result);
    }

    public static function remove() {
        $model = new Cart();
        $model->deleteUnit('id', request('id'), "user_id = '" . USERID . "' or session = '" . session_id() . "'");
        $result = [
            'callback' => 'getCart()'
        ];
        echo json_encode($result);
    }

}