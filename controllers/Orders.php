<?php

class OrdersController {

    public static function index() {
        global $orders;
        global $pageTitle;

        $orders = Orders::getUnits("user_id = '" . USERID . "' or session = '" . session_id() . "'", "date desc");
        foreach ($orders as $order) {
            $order->items = Cart::getItems($order->items);
        }
        $pageTitle = Orders::getTitle();
        view('users/orders', 'main');
    }

    public static function create() {
        if (!user()) {
            echo json_encode([
                'message' => 'Войдите или зарегистрируйтесь и заполните контактные данные',
                'callback' => '$(".user-dropdown").click();'
            ]);
            exit();
        }
        $result = Orders::create($_REQUEST);

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

}