<?php

class OrdersController extends BaseController {

    public static function index() {
        $orders = Orders::getUnits("user_id = '" . USERID . "' or session = '" . session_id() . "'", "date desc");
        foreach($orders as $order) {
            $order->items = Cart::getItems($order->items);
        }
        $pageTitle = Orders::getTitle();
        include(view('users/orders'));
    }

    public static function create() {
        if(!user()) {
            echo json_encode([
                'message' => 'Войдите или зарегистрируйтесь и заполните контактные данные',
                'callback' => '$(".user-dropdown").click();'
            ]);
            exit();
        }
        $result = Orders::create($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            alertResult($result);
            back();
        }
    }

}