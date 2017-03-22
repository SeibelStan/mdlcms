<?php

class Orders extends A_BaseModel {

    public $table = 'orders';
    public $title = 'Заказы';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'       => 'int(11):key_ai',
        'user_id'  => 'varchar(64)',
        'session'  => 'varchar(32)',
        'items'    => 'varchar(20)',
        'state'    => 'int(11)',
        'date'     => 'timestamp::CURRENT_TIMESTAMP',
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'user_id'  => 'Пользователь',
        'session'  => 'Сессия',
        'items'    => 'Предметы',
        'state'    => 'Статус',
        'date'     => 'Дата добавления'
    ];

    public function create($data) {
        global $db;
        $model = new Cart();
        $cartItems = $model->get();
        $data['items'] = [];
        foreach($cartItems as $cartItem) {
            array_push($data['items'], $cartItem->cart_id);
        }
        $data['items'] = implode(',', $data['items']);
        $data['user_id'] = USERID;
        $data['session'] = session_id();
        $this->saveUnit(0, $data);
        $lid = $db->insert_id;

        foreach($cartItems as $cartItem) {
            dbu("update " . $model->getTable() . " set order_id = '$lid' where id = '$cartItem->cart_id'");
        }

        return [
            'message' => 'Заказ №' . $lid . ' создан и вскоре будет обработан',
            'messageType' => 'success',
            'callback' => 'getCart()',
            'items' => $data['items']
        ];
    }

}