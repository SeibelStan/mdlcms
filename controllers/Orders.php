<?php

class OrdersController extends BaseController {

    public static function index() {
        $model = new Orders();
        $cartModel = new Cart();
        $orders = $model->getUnits("user_id = '" . USERID . "' or session = '" . session_id() . "'", "date desc");
        foreach($orders as $order) {
            $order->items = $cartModel->getItems($order->items);
        }
        $pageTitle = $model->getTitle();
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
        $model = new Orders();
        $result = $model->create($_REQUEST);
        
        if(getJS()) {
            echo json_encode($result);
        }
        else {
            back();
        }
    }

}