<?php

class Users extends A_BaseModel {

    public $table = 'users';
    public $title = 'Пользователи';
    public $addable = true;
    public $removable = true;
    public $fields = [
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
    public $fillable = ['full_name', 'tel', 'email', 'address', 'about', 'password', 'reflink'];
    public $required = ['full_name', 'email'];
    public $inputTypes = [
        'password' => 'password',
        'hash'     => 'hidden'
    ];
    public $pattern = [
        'login' => ['[A-z][A-z_0-9]{2,50}', '3+ символов, может содержать буквы, цифры и _'],
        'full_name' => ['[А-яA-z ]{3,50}', '3+ символов, может содержать буквы и пробел'],
        'password' => ['.{6,128}', '6+ символов']
    ];
    public $noEmpty = ['dateup'];
    public $titles = [
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

    public function login($data) {
        $attempt = Attempts::add('login', $data);
        if($attempt->action) {
            return $attempt;
        }

        $lowerLogin = mb_strtolower($data['login']);
        $user = arrayFirst($this->getUnits("(lower(login) = '$lowerLogin' or lower(email) = '$lowerLogin')
            and password = '" . $data['password'] . "' and active"));

        if(!$user) {
            return [
                'message' => 'Неверный логин или пароль'
            ];
        }

        session('user_id', $user->id);

        $this->save([
            'login_date' => dateNowFull()
        ], $user->id);

        return [
            'message' => 'Успешно',
            'type' => 'success',
            'callback' => 'location.href = "' . $_SERVER['HTTP_REFERER'] . '";'
        ];
    }

    public function register($data) {
        global $db;
        $attempt = Attempts::add('register', $data);
        if($attempt->action) {
            return $attempt;
        }

        foreach($data as &$row) {
            $row = clear($row);
        }

        $user = $this->getByField('lower(login)', mb_strtolower($data['login']), "and active");
        
        if($user) {
            return [
                'message' => 'Пользователь существует'
            ];
        }

        $loginCorrect = $this->checkPattern('login', $data['login']);
        if(!$loginCorrect) {
            return [
                'message' => $this->pattern['login'][1]
            ];
        }

        $passwordCorrect = $this->checkPattern('password', $data['password']);
        if(!$passwordCorrect) {
            return [
                'message' => $this->pattern['password'][1]
            ];
        }

        $data['hash'] = hashGen();
        $data['active'] = 1;

        if($data['reflink']) {
            $referrer = $this->getByField('reflink', $data['reflink']);
            if($referrer) {
                $data['referrer'] = $referrer->login;
            }
        }

        foreach($data as &$row) {
            $row = clear($row);
        }

        $id = $this->save($data);
        session('user_id', $id);

        $mailText = sprintf(
            file_get_contents('views/mail/register.html'),
            SITE_DOMAIN,
            SITE_NAME
        );
        if(MAILS) {
            smail('Благодарим Вас за регистрацию на нашем сайте!', $mailText, $data['email']);
        }

        return [
            'message' => 'Получилось!',
            'type' => 'success',
            'callback' => 'location.href = "' . ROOT . 'users";',
            'user' => user($db->insert_id)
        ];
    }

    public function remind($data) {
        $attempt = Attempts::add('remind', $data);
        if($attempt->action) {
            return $attempt;
        }

        $user = arrayFirst($this->getUnits("(lower(login) = '$lowerLogin' or lower(email) = '$lowerLogin')
            and active"));

        if($user && $user->email) {
            $mailText = sprintf(
                file_get_contents('views/mail/remind.html'),
                passGen(8),
                $user->hash
            );

            if(MAILS) {
                smail('Восстановление пароля для пользователя ' . $user->login, $mailText, $user->email);
            }

            return [
                'message' => 'Письмо с паролем выслано на почту',
                'type' => 'success'
            ];
        }
        else {
            return [
                'message' => 'Пользователя не существует или не указана почта'
            ];
        }
    }

    public function restore($data) {
        $user = $this->getByField('hash', $data['hash']);

        if($user) {
            session('user_id', $user->id);

            $this->save([
                'hash' => hashGen(),
                'password' => $data['pass']
            ], $user->id);
            return 1;
        }
        return 0;
    }      

    public function getReferrals($referrer) {
        $model = new Users();
        return $model->getUnits('login', $referrer);
    }

    public function saveProfile($data) {
        foreach($data as $k => &$row) {
            if(!preg_match('/password/', $k)) {
                $row = clear($row);
            }
        }

        if(isset($data['password'])) {
            if(
                isset($data['password_confirm']) &&
                $data['password'] != $data['password_confirm']
            ) {
                return [
                    'message' => 'Пароли не совпадают'
                ];
            }
            unset($data['password_confirm']);

            $passwordCorrect = $this->checkPattern('password', $data['password']);
            if(!$passwordCorrect) {
                unset($data['password']);
            }
        }

        $result = $this->save($data, USERID, true);
        if($result) {
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