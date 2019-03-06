<?php

class Attempts extends A_BaseModel {

    public static $table = 'attempts';
    public static $title = 'Попытки';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'      => 'int(11)::key_ai',
        'type'    => 'varchar(20)',
        'ip'      => 'varchar(20)',
        'user_id' => 'int(11)',
        'data'    => 'varchar(255)',
        'date'    => 'timestamp:NOW()',
    ];

    public static $inputTypes = [
        'id' => 'hidden'
    ];
    public static $titles = [
        'type'    => 'Тип',
        'ip'      => 'IP-адрес',
        'user_id' => 'ID пользователя',
        'date'    => 'Данные',
        'date'    => 'Дата добавления'
    ];

    public static function add($type, $data = []) {
        if (!ATTEMPTS) {
            return (object) [
                'action' => ''
            ];
        }

        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        Attempts::save([
            'type'    => $type,
            'ip'      => $data->ip,
            'user_id' => $data->user_id,
            'data'    => json_encode($data)
        ]);

        return Attempts::check($type);
    }

    public static function check($type, $data = []) {
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        $guardCounts = [
            'view'     => [500, 600],
            'login'    => [5, 10],
            'register' => [1, 10],
            'remind'   => [2, 10],
            'feedback' => [1, 10],
        ];
        $guardMessages = [
            'login'    => [
                'Попробуйте позже или восстановите пароль. Не продолжайте вводить неподходящие данные!',
                'Заблокированы за перебор паролей'
            ]
        ];

        $units = Attempts::getUnits("(ip = '$data->ip' or (user_id <> 0 and user_id = '$data->user_id')) and type = '$type'");

        $count = count($units);
        $action = '';
        $message = '';
        if ($count >= $guardCounts[$type][0]) {
            $action = 'restrict';
            $message = isset($guardMessages[$type]) ? $guardMessages[$type][0] : 'Попробуйте позже';
        }
        if ($count >= $guardCounts[$type][0]) {
            if (!Bans::check()) {
                Bans::add();
            }
            $action = 'ban';
            $message = isset($guardMessages[$type]) ? $guardMessages[$type][1] : 'Заблокированы за подозрительную активность';
        }

        return (object) [
            'type'    => $type,
            'action'  => $action,
            'count'   => $count,
            'guard'   => $guardCounts[$type],
            'message' => $message
        ];
    }

    public static function reset() {
        echo Attempts::clear();
        back();
    }

}