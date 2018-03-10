<?php

class CartController extends BaseController {

    public static function get() {
        $model = new Cart();
        $result = $model->get();

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            back();
        }
    }

    public static function add() {
        $model = new Cart();
        $result = $model->add($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            back();
        }
    }

    public static function remove() {
        $model = new Cart();
        $model->delete('id', request('id'), "user_id = '" . USERID . "' or session = '" . session_id() . "'");
        $result = [
            'callback' => 'getCart()'
        ];

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            back();
        }
    }

}