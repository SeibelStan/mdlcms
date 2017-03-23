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
        'isadmin'    => 'int(1)::0',
        'active'     => 'int(1)::1',
        'hash'       => 'varchar(64)',
        'login_date' => 'datetime',
        'register'   => 'timestamp::CURRENT_TIMESTAMP',
        'dateup'     => 'timestamp::CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ];
    public $fillable = ['full_name', 'tel', 'email', 'address', 'about'];
    public $required = ['full_name', 'email'];
    public $pattern = [
        'login' => ['[А-яA-z_0-9]{3,50}', 'Длиннее трёх символов, может содержать буквы, цифры и _'],
        'full_name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел']
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
        'isadmin'    => 'Администратор',
        'active'     => 'Активный',
        'login_date' => 'Последний вход',
        'hash'       => 'Хэш',
        'register'   => 'Дата регистрации',
        'dateup'     => 'Дата обновления'
    ];

    public static function login($data) {
        if(ATTEMPTS) {
            dbi("insert into attempts (type, data, ip) values ('login', '" . json_encode($data) . "', '" . USER_IP . "')");
            $attempts = dbs("select * from attempts where type = 'login' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 10) {
                dbi("insert into banned_ip (ip) values ('" . USER_IP . "')");
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

        $user = dbs("select * from users
            where login = '" . $data['login'] . "' and password = '" . $data['password'] . "'
            and active = 1 limit 1", true);

        if(!$user) {
            return [
                'message' => 'Неверный логин или пароль'
            ];
        }

        session('user_id', $user->id);
        dbu("update users set login_date = '" . dateNowFull() . "' where id = '$user->id'");
        return [
            'message' => 'Неверный логин или пароль',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . ($user->isadmin ? 'admin/edit-models' : 'users') . '";'
        ];
    }

    public function register($data) {
        global $db;
        if(ATTEMPTS) {
            $attempts = dbs("select * from attempts where type = 'register' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 5) {
                return [
                    'message' => 'Попробуйте позже'
                ];
            }
            dbi("insert into attempts (type, data, ip) values ('register', '" . json_encode($data) . "', '" . USER_IP . "')");
        }

        $user = dbs("select * from users WHERE login = '" . $data['login'] . "' and active = 1");

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
        $this->saveUnit(0, $data);
        return [
            'message' => 'Получилось!',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . 'users";',
            'user' => getUser($db->insert_id)
        ];
    }

}