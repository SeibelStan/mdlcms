<?php

class Bans extends A_BaseModel {

    public static $table = 'bans';
    public static $title = 'Баны';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'      => 'int(11)::key_ai',
        'ip'      => 'varchar(20)',
        'user_id' => 'int(11)',
        'date'    => 'timestamp:NOW()',
    ];
    public static $inputTypes = [
        'id' => 'hidden'
    ];
    public static $titles = [
        'ip'      => 'IP-адрес',
        'user_id' => 'ID пользователя',
        'date'    => 'Дата добавления'
    ];

    public static function add($data = []) {
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        Bans::save([
            'ip'      => $data->ip,
            'user_id' => $data->user_id,
        ]);
    }

    public static function check($data = []) {
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        return Bans::getUnits("ip = '$data->ip' or (user_id <> 0 and user_id = '$data->user_id')");
    }

}