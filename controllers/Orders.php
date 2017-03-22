<?php

class OrdersController extends BaseController {

    public static function index() {
        $model = new Orders();
        $cartModel = new Cart();
        $orders = $model->getUnits("user_id = '" . USERID . "' or session = '" . session_id() . "'", "date desc");
        foreach($orders as $order) {
            $order->items = $cartModel->getItems($order->items);
        }
        include(view('users/orders'));
    }

    public static function create() {
        $model = new Orders();
        $result = $model->create($_REQUEST);
        die(json_encode($result));
    }

}