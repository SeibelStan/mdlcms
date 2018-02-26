<?php

class Attempts extends A_BaseModel {

    public $table = 'attempts';
    public $title = 'Попытки';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'      => 'int(11)::key_ai',
        'type'    => 'varchar(20)',
        'ip'      => 'varchar(20)',
        'user_id' => 'int(11)',
        'data'    => 'varchar(255)',
        'date'    => 'timestamp:NOW()',
    ];

    public $inputTypes = [
        'id' => 'hidden'
    ];
    public $titles = [
        'type'    => 'Тип',
        'ip'      => 'IP-адрес',
        'user_id' => 'ID пользователя',
        'date'    => 'Данные',
        'date'    => 'Дата добавления'
    ];

    public static function add($type, $data = []) {
        if(!ATTEMPTS) {
            return (object) [
                'action' => ''
            ];
        }

        $data = (object)$data;
        $data->ip = isset($data->ip) ? $data->ip : USER_IP;
        $data->user_id = isset($data->user_id) ? $data->login : USERID;

        $model = new Attempts();
        $model->save([
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
            'register' => [5, 10],
            'remind'   => [5, 10],
            'feedback' => [2, 10],
        ];
        $guardMessages = [
            'login'    => [
                'Попробуйте позже или восстановите пароль. Не продолжайте вводить неподходящие данные!',
                'Заблокированы за перебор паролей'
            ]
        ];

        $model = new Attempts();
        $units = $model->getUnits("(ip = '$data->ip' or user_id = '$data->user_id') and type = '$type'");

        $count = count($units);
        $action = '';
        $message = '';
        if($count >= $guardCounts[$type][0]) {
            $action = 'restrict';
            $message = isset($guardMessages[$type]) ? $guardMessages[$type][0] : 'Попробуйте позже';
        }
        if($count >= $guardCounts[$type][0]) {
            if(!Bans::check()) {
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
        $model = new Attempts();
        echo $model->clear();
        back();
    }

}