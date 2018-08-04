<?php

class Users extends A_BaseModel {

    public static $table = 'users';
    public static $title = 'Пользователи';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'         => 'int(11)::key_ai',
        'login'      => 'varchar(100)',
        'full_name'  => 'varchar(100)',
        'tel'        => 'varchar(50)',
        'email'      => 'varchar(100)',
        'password'   => 'varchar(128)',
        'address'    => 'varchar(255)',
        'about'      => 'varchar(1000)',
        'roles'      => 'varchar(50)',
        'active'     => 'int(1):1',
        'referrer'   => 'varchar(100)',
        'reflink'    => 'varchar(100)',
        'hash'       => 'varchar(64)',
        'login_date' => 'timestamp:NOW()',
        'register'   => 'timestamp:NOW()',
        'dateup'     => 'timestamp:NOW()'
    ];
    public static $fillable = ['full_name', 'tel', 'email', 'address', 'about', 'password', 'reflink'];
    public static $required = ['full_name', 'email'];
    public static $inputTypes = [
        'password' => 'password',
        'hash'     => 'hidden'
    ];
    public static $pattern = [
        'login'     => ['[A-z][A-z_0-9]{2,50}', '3+ символов, может содержать буквы, цифры и _'],
        'full_name' => ['[А-яA-z ]{3,50}', '3+ символов, может содержать буквы и пробел'],
        'password'  => ['.{6,128}', '6+ символов']
    ];
    public static $noEmpty = ['dateup'];
    public static $titles = [
        'id'         => 'ID',
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
        'referrer'   => 'Пригласивший',
        'reflink'    => 'Ссылка для приглашения',
        'hash'       => 'Хэш',
        'register'   => 'Дата регистрации',
        'dateup'     => 'Дата обновления'
    ];

    public static function login($data) {
        $attempt = Attempts::add('login', $data);
        if ($attempt->action) {
            return $attempt;
        }

        $lowerLogin = mb_strtolower($data['login']);
        $user = arrayFirst(static::getUnits("(lower(login) = '$lowerLogin' or lower(email) = '$lowerLogin')
            and password = '" . $data['password'] . "' and active"));

        if (!$user) {
            return [
                'message' => 'Неверный логин или пароль'
            ];
        }

        session('user_id', $user->id);

        static::save([
            'login_date' => date('Y-m-d H:i:s')
        ], $user->id);

        return [
            'message' => 'Успешно',
            'type' => 'success',
            'callback' => 'location.href = "' . $_SERVER['HTTP_REFERER'] . '";'
        ];
    }

    public static function register($data) {
        global $db;
        $attempt = Attempts::add('register', $data);
        if ($attempt->action) {
            return $attempt;
        }

        foreach ($data as &$row) {
            $row = clear($row);
        }

        $user = static::getByField('lower(login)', mb_strtolower($data['login']), "and active");

        if ($user) {
            return [
                'message' => 'Пользователь существует'
            ];
        }

        $loginCorrect = static::checkPattern('login', $data['login']);
        if (!$loginCorrect) {
            return [
                'message' => static::$pattern['login'][1]
            ];
        }

        $passwordCorrect = static::checkPattern('password', $data['password']);
        if (!$passwordCorrect) {
            return [
                'message' => static::$pattern['password'][1]
            ];
        }

        $data['hash'] = hashGen();
        $data['active'] = 1;

        if ($data['reflink']) {
            $referrer = static::getByField('reflink', $data['reflink']);
            if ($referrer) {
                $data['referrer'] = $referrer->login;
            }
        }

        foreach ($data as &$row) {
            $row = clear($row);
        }

        $id = static::save($data);
        session('user_id', $id);

        $mailText = sprintf(
            file_get_contents('views/mail/register.html'),
            FULLHOST,
            SITE_NAME
        );
        if (MAILS) {
            smail('Благодарим Вас за регистрацию на нашем сайте!', $mailText, $data['email']);
        }

        return [
            'message' => 'Получилось!',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . 'users";',
            'user' => user($db->insert_id)
        ];
    }

    public static function remind($data) {
        $attempt = Attempts::add('remind', $data);
        if ($attempt->action) {
            return $attempt;
        }

        $lowerLogin = mb_strtolower($data['login']);
        $user = arrayFirst(static::getUnits("(lower(login) = '$lowerLogin' or lower(email) = '$lowerLogin')
            and active"));

        if ($user && $user->email) {
            $mailText = sprintf(
                file_get_contents('views/mail/remind.html'),
                hashGen(8),
                $user->hash,
                FULLHOST
            );

            if (MAILS) {
                smail('Восстановление пароля для пользователя ' . $user->login, $mailText, $user->email);
            }

           $maskEmail = preg_replace('/^(.).+?(.)@/', '$1***$2@', $user->email);

            return [
                'message' => 'Письмо с паролем выслано на почту ' . $maskEmail,
                'type' => 'success'
            ];
        }
        else {
            return [
                'message' => 'Пользователя не существует или не указана почта'
            ];
        }
    }

    public static function restore($data) {
        $user = static::getByField('hash', $data['hash']);

        if ($user) {
            session('user_id', $user->id);

            static::save([
                'hash' => hashGen(),
                'password' => $data['pass']
            ], $user->id);
            return 1;
        }
        return 0;
    }

    public static function getReferrals($referrer) {
        return Users::getUnits('login', $referrer);
    }

    public static function saveProfile($data) {
        foreach ($data as $k => &$row) {
            if (!preg_match('/password/', $k)) {
                $row = clear($row);
            }
        }

        if (isset($data['password'])) {
            if (
                isset($data['password_confirm']) &&
                $data['password'] != $data['password_confirm']
            ) {
                return [
                    'message' => 'Пароли не совпадают'
                ];
            }
            unset($data['password_confirm']);

            $passwordCorrect = static::checkPattern('password', $data['password']);
            if (!$passwordCorrect) {
                unset($data['password']);
            }
        }

        $result = static::save($data, USERID, true);
        if ($result) {
            return [
                'message' => 'Сохранено',
                'type' => 'success'
            ];
        }
        else {
            return [
                'message' => 'Не изменено'
            ];
        }
    }

}
