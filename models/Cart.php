<?php

class Cart extends A_BaseModel {

    public static $table = 'cart';
    public static $title = 'Корзина';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'       => 'int(11)::key_ai',
        'user_id'  => 'varchar(64)',
        'session'  => 'varchar(32)',
        'model'    => 'varchar(20)',
        'item_id'  => 'int(11)',
        'count'    => 'int(11):1',
        'order_id' => 'int(11):0',
        'date'     => 'timestamp:NOW()',
    ];
    public static $inputTypes = [
        'id' => 'hidden'
    ];
    public static $noEmpty = ['date', 'dateup'];
    public static $titles = [
        'user_id'  => 'Пользователь',
        'session'  => 'Сессия',
        'model'    => 'Модель',
        'item_id'  => 'Номер предмета',
        'count'    => 'Количество',
        'order_id' => 'Заказ',
        'date'     => 'Дата добавления'
    ];

    public static $cartable = ['catalog'];

    public static function get() {
        $result = [];
        $cartItems = dbs("* from " . static::getTable() . "
            where (user_id = '" . USERID . "' or session = '" . session_id() . "')
            and order_id = 0 order by date desc");

        foreach ($cartItems as $cartItem) {
            $itemModel = $cartItem->model;
            $item = arrayFirst(dbs("* from " . $itemModel::getTable() . " where id = '$cartItem->item_id'"));
            $item->count = $cartItem->count;
            $item->cart_id = $cartItem->id;
            $item->model = $cartItem->model;
            array_push($result, $item);
        }
        return $result;
    }

    public static function getItems($items) {
        $items = explode(',', $items);
        $result = [];
        foreach ($items as $itemId) {
            $cartItem = static::getByField('id', $itemId);
            if (!$cartItem) {
                continue;
            }
            $itemModel = $cartItem->model;
            $item = $itemModel::getByField('id', $cartItem->item_id);
            $cartItem->title = $item->title;
            $cartItem->price = $item->price;
            $cartItem->image = $item->image;
            $cartItem->url = $item->url;
            $cartItem->model = strtolower($cartItem->model);
            array_push($result, $cartItem);
        }
        return $result;
    }

    public static function add($data) {
        global $db;
        if (!in_array(strtolower($data['model']), static::$cartable)) {
            return [
                'message' => 'Неправильный тип предмета',
            ];
        }
        $data['user_id'] = USERID;
        $data['session'] = session_id();

        $itemId = $data['item_id'];
        $item = (array)static::getByField('item_id', $itemId, "and user_id = '" . $data['user_id'] . "' or session = '" . $data['session'] . "'");
        if ($item) {
            $count = $data['count'];
            $data = $item;
            $data['count'] += $count;
            $data['count'] = $data['count'] >= 1 ? $data['count'] : 1;
            static::save($data, $item['id']);
            $message = 'Количество обновлено';
        }
        else {
            $data['count'] = $data['count'] >= 1 ? $data['count'] : 1;
            static::save($data);
            $message = 'Предмет добавлен в корзину';
        }

        return [
            'message' => $message,
            'type' => 'success',
            'callback' => 'getCart()'
        ];
    }

}