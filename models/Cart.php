<?php

class Cart extends A_BaseModel {

    public $userId;

    function __construct($userId = false) {
        $this->userId = $userId;
    }

    public $table = 'cart';
    public $title = 'Корзина';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'      => 'int(11):key_ai',
        'user_id' => 'varchar(64)',
        'model'   => 'varchar(20)',
        'item_id' => 'int(11)',
        'count'   => 'int(11)::1',
        'date'    => 'timestamp::CURRENT_TIMESTAMP',
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'user_id' => 'int(11)',
        'model'   => 'Модель',
        'item_id' => 'Номер предмета',
        'count'   => 'Количество',
        'date'    => 'Дата добавления'
    ];

    public function get() {
        $result = [];
        $cartItems = dbs("select * from " . $this->getTable() . " where user_id = '$this->userId' order by date desc");
        foreach($cartItems as $cartItem) {
            $cartItem->model = strtolower($cartItem->model);
            $item = dbs("select * from $cartItem->model where id = '$cartItem->item_id'", true);
            $item->count = $cartItem->count;
            $item->cart_id = $cartItem->id;
            $item->model = $cartItem->model;
            array_push($result, $item);
        }
        return $result;
    }

    public function add($data) {
        global $db;
        $data['user_id'] = $this->userId;
        $this->saveUnit(0, $data);
        return [
            'message' => 'Предмет добавлен в корзину',
            'messageType' => 'success',
            'callback' => 'getCart()'
        ];
    }

}