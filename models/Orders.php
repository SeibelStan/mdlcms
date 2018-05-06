<?php

class Orders extends A_BaseModel {

    public static $table = 'orders';
    public static $title = 'Заказы';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'      => 'int(11)::key_ai',
        'user_id' => 'varchar(64)',
        'links'   => 'text',
        'tel'     => 'varchar(50)',
        'address' => 'varchar(255)',
        'state'   => 'int(2):0',
        'items'   => 'varchar(20)',
        'session' => 'varchar(32)',
        'date'    => 'timestamp:NOW()',
        'dateup'  => 'timestamp:NOW()'
    ];
    public static $noEmpty = ['date', 'dateup'];
    public static $titles = [
        'id'      => '№ заказа',
        'user_id' => 'Пользователь',
        'links'   => 'Ссылки',
        'tel'     => 'Телефон',
        'address' => 'Адрес',
        'state'   => 'Статус',
        'items'   => 'Предметы',
        'session' => 'Сессия',
        'date'    => 'Дата добавления',
        'dateup'  => 'Дата обновления'
    ];

    public static function create($data) {
        global $db;
        $cartItems = Cart::get();
        $data['items'] = [];
        $data['links'] = [];
        foreach($cartItems as $cartItem) {
            array_push($data['items'], $cartItem->cart_id);
            array_push($data['links'], FULLHOST . ROOT . $cartItem->model . '/' . $cartItem->id);
        }
        $data['items'] = implode(',', $data['items']);
        $data['links'] = implode("\n", $data['links']);
        $data['user_id'] = USERID;
        $data['session'] = session_id();
        $data['tel'] = user()->tel;
        $data['address'] = user()->address;

        static::save($data);
        $lid = $db->insert_id;

        foreach($cartItems as $cartItem) {
            dbu(Cart::getTable() . " set order_id = '$lid' where id = '$cartItem->cart_id'");
        }

        return [
            'message' => 'Заказ №' . $lid . ' создан и вскоре будет обработан',
            'type' => 'success',
            'callback' => 'getCart()',
            'items' => $data['items']
        ];
    }

}