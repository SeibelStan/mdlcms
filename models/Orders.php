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
        'date'     => 'timestamp::CURRENT_TIMESTAMP',
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'user_id'  => 'Пользователь',
        'session'  => 'Сессия',
        'items'    => 'Предметы',
        'date'     => 'Дата добавления'
    ];

    public function create($data) {
        return [
            'message' => 'Заказ №' . $lid . ' создан и вскоре будет обработан',
            'messageType' => 'success'
        ];
    }

}