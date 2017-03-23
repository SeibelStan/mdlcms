<?php

class Cart extends A_BaseModel {

    public $table = 'cart';
    public $title = 'Корзина';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'       => 'int(11):key_ai',
        'user_id'  => 'varchar(64)',
        'session'  => 'varchar(32)',
        'model'    => 'varchar(20)',
        'item_id'  => 'int(11)',
        'count'    => 'int(11)::1',
        'order_id' => 'int(11)',
        'date'     => 'timestamp::CURRENT_TIMESTAMP',
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'user_id'  => 'Пользователь',
        'session'  => 'Сессия',
        'model'    => 'Модель',
        'item_id'  => 'Номер предмета',
        'count'    => 'Количество',
        'order_id' => 'Заказ',
        'date'     => 'Дата добавления'
    ];

    public $cartable = ['Catalog'];

    public function get() {
        $result = [];
        $cartItems = dbs("select * from " . $this->getTable() . "
            where (user_id = '" . USERID . "' or session = '" . session_id() . "')
            and order_id = 0 order by date desc");

        foreach($cartItems as $cartItem) {
            $model = new $cartItem->model();
            $item = dbs("select * from " . $model->getTable() . " where id = '$cartItem->item_id'", true);
            $item->count = $cartItem->count;
            $item->cart_id = $cartItem->id;
            $item->model = $cartItem->model;
            array_push($result, $item);
        }
        return $result;
    }

    public function getItems($items) {
        $items = explode(',', $items);
        $result = [];
        foreach($items as $itemId) {
            $cartItem = $this->getByField('id', $itemId);          
            $modelName = $cartItem->model;
            $itemModel = new $modelName();
            $item = $itemModel->getByField('id', $cartItem->item_id);
            $cartItem->title = $item->title;
            $cartItem->price = $item->price;
            $cartItem->image = $item->image;
            $cartItem->url = $item->url ?: $item->id;
            $cartItem->model = strtolower($cartItem->model);
            array_push($result, $cartItem);
        }
        return $result;
    }

    public function add($data) {
        global $db;
        if(!in_array($data['model'], $this->cartable)) {
            return [
                'message' => 'Не правильный тип предмета',
            ];
        }
        $data['user_id'] = USERID;
        $data['session'] = session_id();
        $this->saveUnit(0, $data);
        return [
            'message' => 'Предмет добавлен в корзину',
            'type' => 'success',
            'callback' => 'getCart()'
        ];
    }

}