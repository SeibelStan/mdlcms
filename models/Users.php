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
        'referal'    => 'varchar(100)',       
        'hash'       => 'varchar(64)',
        'login_date' => 'timestamp::NOW()',
        'register'   => 'timestamp::NOW()',
        'dateup'     => 'timestamp::NOW()'
    ];
    public $fillable = ['full_name', 'tel', 'email', 'address', 'about', 'password'];
    public $required = ['full_name', 'email'];
    public $inputTypes = [
        'password' => 'password'
    ];
    public $pattern = [
        'login' => ['[А-яA-z_0-9]{3,50}', 'Длиннее трёх символов, может содержать буквы, цифры и _'],
        'full_name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел'],
        'password' => ['.{6,128}', 'Длиннее шести символов']
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
        'referal'    => 'Реферал',        
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
                Bans::add();
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

        $user = arrayFirst(dbs("* from " . $this->getTable() . "
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

        $user = dbs("* from " . $this->getTable() . " WHERE login = '" . $data['login'] . "' and active = 1");

        if($user) {
            return [
                'message' => 'Пользователь существует'
            ];
        }

        $loginCorrect = preg_match('/^' . $this->pattern['login'][0] . '$/', $data['login']);
        $passwordCorrect = preg_match('/^' . $this->pattern['password'][0] . '$/', $data['password']);
        if(!$loginCorrect || !$passwordCorrect) {
            return [
                'message' => 'Проверьте данные'
            ];
        }

        $data['hash'] = hashGen();
        $data['active'] = 'on';
        $id = $this->save(0, $data);
        session('user_id', $id);

        $mailText = sprintf(
            file_get_contents('views/mail/register.html')
        );
        smail('Благодарим Вас за регистрацию на нашем сайте!', $mailText, $data['email']);

        return [
            'message' => 'Получилось!',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . 'users";',
            'user' => Helpers::getUser($db->insert_id)
        ];
    }

    public function remind($data) {
        if(ATTEMPTS) {
            dbi("into attempts (type, data, ip) values ('remind', '" . json_encode($data) . "', '" . USER_IP . "')");
            $attempts = dbs("* from attempts where type = 'remind' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 2) {
                return [
                    'message' => 'Попробуйте позже',
                    'callback' => '$(".modal__close").click();'                    
                ];
            }
        }

        $user = arrayFirst(dbs("* from " . $this->getTable() . "
            where (login = '" . $data['login'] . "' or email = '" . $data['login'] . "')
            and active = 1"));

        if($user) {
            $mailText = sprintf(
                file_get_contents('views/mail/remind.html'),
                passGen(8),
                $user->hash
            );
            smail('Восстановление пароля для пользователя ' . $user->login, $mailText, $user->email);

            return [
                'message' => 'Письмо с паролем выслано на почту',
                'type' => 'success'
            ];
        }
        else {
            return [
                'message' => 'Пользователя не существует'
            ];
        }
    }

    public function restore($data) {
        $user = $this->getByField('hash', $data['hash']);

        if($user) {
            session('user_id', $user->id);

            $this->save($user->id, [
                'hash' => hashGen(),
                'password' => $data['pass']
            ]);
            return 1;
        }
        return 0;
    }

}