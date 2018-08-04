<?php

class CartController {

    public static function get() {
        $result = Cart::get();

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            back();
        }
    }

    public static function add() {
        $result = Cart::add($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

    public static function remove() {
        Cart::delete('id', request('id'), "user_id = '" . USERID . "' or session = '" . session_id() . "'");
        $result = [
            'callback' => 'getCart()'
        ];

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

}