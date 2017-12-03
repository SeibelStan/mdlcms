<?php

class Bans extends A_BaseModel {

    public $table = 'bans';
    public $title = 'Баны';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'   => 'int(11)::key_ai',
        'ip'   => 'varchar(20)',
        'date' => 'timestamp:NOW()',
    ];
    public $inputTypes = [
        'id' => 'hidden'
    ];
    public $titles = [
        'ip'   => 'IP-адрес',
        'date' => 'Дата добавления'
    ];

    public static function add($ip = '') {
        $ip = $ip ?: USER_IP;
        $model = new Bans();
        $model->save(0, [
            'ip' => $ip
        ]);
    }

    public static function check($ip = '') {
        $ip = $ip ?: USER_IP;       
        $model = new Bans();
        return $model->getByField('ip', $ip);
    }

}