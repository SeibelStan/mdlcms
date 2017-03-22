<?php

class OrdersController extends BaseController {

    public static function index() {
        $model = new Orders();
        $orders = $model->getUnits("user_id = '" . USERID . "' or session = '" . session_id() . "'", "date desc");
        include(view('users/orders'));
    }

    public static function add() {
        $model = new Orders();
        $result = $model->create($_REQUEST);
        die(json_encode($result));
    }

}