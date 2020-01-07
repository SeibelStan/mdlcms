<?php

class Attempts extends A_BaseModel {

    public static $table = 'attempts';
    public static $title = 'Попытки';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'      => 'int(11)::key_ai',
        'action'  => 'varchar(20)',
        'ip'      => 'varchar(20)',
        'user_id' => 'int(11)',
        'data'    => 'varchar(255)',
        'date'    => 'timestamp:NOW()',
    ];

    public static $inputactions = [
        'id' => 'hidden'
    ];
    public static $titles = [
        'action'    => 'Тип',
        'ip'      => 'IP-адрес',
        'user_id' => 'ID пользователя',
        'date'    => 'Данные',
        'date'    => 'Дата добавления'
    ];

    public static function add($action, $data = []) {
        if (!ATTEMPTS) {
            return (object) [
                'action' => ''
            ];
        }

        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        Attempts::save([
            'action'    => $action,
            'ip'      => $data->ip,
            'user_id' => $data->user_id,
            'data'    => json_encode($data)
        ]);

        return Attempts::check($action);
    }

    public static function check($action, $data = []) {
        global $guardCounts;
        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        $guardMessages = [
            'login'    => [
                'Попробуйте позже или восстановите пароль. Не продолжайте вводить неподходящие данные!',
                'Заблокированы за перебор паролей'
            ]
        ];

        $units = Attempts::getUnits("(ip = '$data->ip' or (user_id <> 0 and user_id = '$data->user_id')) and action = '$action'");

        $count = count($units);
        $reason = '';
        $message = '';
        if ($count >= $guardCounts[$action][0]) {
            $reason = 'restrict';
            $message = isset($guardMessages[$action]) ? $guardMessages[$action][0] : 'Попробуйте позже';
        }
        if ($count >= $guardCounts[$action][0]) {
            if (!Bans::check()) {
                Bans::add();
            }
            $reason = 'ban';
            $message = isset($guardMessages[$action]) ? $guardMessages[$action][1] : 'Заблокированы за подозрительную активность';
        }

        return (object) [
            'action'  => $action,
            'reason'  => $reason,
            'count'   => $count,
            'guard'   => $guardCounts[$action],
            'message' => $message
        ];
    }

    public static function reset() {
        echo Attempts::clear();
        back();
    }

}