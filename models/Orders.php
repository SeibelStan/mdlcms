<?php

class Orders extends A_BaseModel {

    public $table = 'orders';
    public $title = 'Заказы';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'      => 'int(11):key_ai',
        'user_id' => 'varchar(64)',
        'links'   => 'text',
        'tel'     => 'varchar(50)',
        'address' => 'varchar(255)',
        'state'   => 'int(11)',
        'date'    => 'timestamp::CURRENT_TIMESTAMP',
        'items'   => 'varchar(20)',
        'session' => 'varchar(32)'
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'id'      => '№ заказа',
        'user_id' => 'Пользователь',
        'links'   => 'Ссылки',
        'tel'     => 'Телефон',
        'address' => 'Адрес',
        'state'   => 'Статус',
        'date'    => 'Дата добавления',
        'items'   => 'Предметы',
        'session' => 'Сессия'
    ];

    public function create($data) {
        global $db;
        $model = new Cart();
        $cartItems = $model->get();
        $data['items'] = [];
        $data['links'] = [];
        foreach($cartItems as $cartItem) {
            array_push($data['items'], $cartItem->cart_id);
            array_push($data['links'], SITE_DOMAIN . ROOT . $cartItem->model . '/' . $cartItem->id);
        }
        $data['items'] = implode(',', $data['items']);
        $data['links'] = implode("\n", $data['links']);
        $data['user_id'] = USERID;
        $data['session'] = session_id();
        $data['tel'] = Helpers::getUser()->tel;
        $data['address'] = Helpers::getUser()->address;
        $this->saveUnit(0, $data);
        $lid = $db->insert_id;

        foreach($cartItems as $cartItem) {
            dbu("update " . $model->getTable() . " set order_id = '$lid' where id = '$cartItem->cart_id'");
        }

        return [
            'message' => 'Заказ №' . $lid . ' создан и вскоре будет обработан',
            'type' => 'success',
            'callback' => 'getCart()',
            'items' => $data['items']
        ];
    }

}