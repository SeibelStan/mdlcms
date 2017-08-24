<?php

class Users extends A_BaseModel {

    public $table = 'users';
    public $title = 'Пользователи';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'         => 'int(11):key_ai',
        'login'      => 'varchar(100)',
        'full_name'  => 'varchar(100)',
        'tel'        => 'varchar(50)',
        'email'      => 'varchar(100)',
        'password'   => 'varchar(128)',
        'address'    => 'varchar(255)',
        'about'      => 'varchar(1000)',
        'roles'      => 'varchar(50)',
        'active'     => 'int(1)::1',
        'hash'       => 'varchar(64)',
        'login_date' => 'timestamp::NOW()',
        'register'   => 'timestamp::NOW()',
        'dateup'     => 'timestamp::NOW()'
    ];
    public $fillable = ['full_name', 'tel', 'email', 'address', 'about'];
    public $required = ['full_name', 'email'];
    public $pattern = [
        'login' => ['[А-яA-z_0-9]{3,50}', 'Длиннее трёх символов, может содержать буквы, цифры и _'],
        'full_name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел'],
        'password' => ['[А-яA-z_0-9]{3,50}', 'Длиннее шести символов']
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'login'      => 'Логин',
        'full_name'  => 'Имя',
        'tel'        => 'Телефон',
        'email'      => 'Емаил',
        'password'   => 'Пароль',
        'address'    => 'Адрес',
        'about'      => 'О себе',
        'roles'      => 'Роли',
        'active'     => 'Активный',
        'login_date' => 'Последний вход',
        'hash'       => 'Хэш',
        'register'   => 'Дата регистрации',
        'dateup'     => 'Дата обновления'
    ];

    public static function login($data) {
        if(ATTEMPTS) {
            dbi("into attempts (type, data, ip) values ('login', '" . json_encode($data) . "', '" . USER_IP . "')");
            $attempts = dbs("* from attempts where type = 'login' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 10) {
                dbi("into banned_ip (ip) values ('" . USER_IP . "')");
                return [
                    'message' => 'Заблокированы за перебор паролей',
                    'callback' => 'location.href = "' . ROOT . '";'
                ];
            }
            if($count_att >= 5) {
                return [
                    'message' => 'Попробуйте позже или восстановите пароль. Не продолжайте вводить неподходящие данные!'
                ];
            }
        }

        $user = arrayFirst(dbs("* from users
            where login = '" . $data['login'] . "' and password = '" . $data['password'] . "'
            and active = 1 limit 1"));

        if(!$user) {
            return [
                'message' => 'Неверный логин или пароль'
            ];
        }

        session('user_id', $user->id);
        dbu("users set login_date = '" . dateNowFull() . "' where id = '$user->id'");
        return [
            'message' => 'Успешно',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . (Helpers::checkRoles($user->roles, 'admin') ? 'admin/edit-models' : 'users') . '";'
        ];
    }

    public function register($data) {
        global $db;
        if(ATTEMPTS) {
            $attempts = dbs("* from attempts where type = 'register' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 5) {
                return [
                    'message' => 'Попробуйте позже'
                ];
            }
            dbi("into attempts (type, data, ip) values ('register', '" . json_encode($data) . "', '" . USER_IP . "')");
        }

        $user = dbs("* from users WHERE login = '" . $data['login'] . "' and active = 1");

        if($user) {
            return [
                'message' => 'Пользователь существует'
            ];
        }
        if(!preg_match('/^[A-z_]{3,50}$/', $data['login']) || strlen($data['password']) < 6) {
            return [
                'message' => 'Проверьте данные'
            ];
        }

        $data['hash'] = newHash();
        $data['active'] = 'on';
        $this->save(0, $data);
        return [
            'message' => 'Получилось!',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . 'users";',
            'user' => Helpers::getUser($db->insert_id)
        ];
    }

}