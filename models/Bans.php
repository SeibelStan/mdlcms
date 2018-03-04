<?php

class Bans extends A_BaseModel {

    public $table = 'bans';
    public $title = 'Баны';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'      => 'int(11)::key_ai',
        'ip'      => 'varchar(20)',
        'user_id' => 'int(11)',
        'date'    => 'timestamp:NOW()',
    ];
    public $inputTypes = [
        'id' => 'hidden'
    ];
    public $titles = [
        'ip'      => 'IP-адрес',
        'user_id' => 'ID пользователя',
        'date'    => 'Дата добавления'
    ];

    public static function add($data = []) {
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        $model = new Bans();
        $model->save([
            'ip'      => $data->ip,
            'user_id' => $data->user_id,
        ]);
    }

    public static function check($data = []) {
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;
    
        $model = new Bans();
        return $model->getUnits("ip = '$data->ip' or (user_id <> 0 and user_id = '$data->user_id')");
    }

}