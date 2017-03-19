<?php

class Users extends A_BaseModel {

    public $table = 'users';
	public $title = 'Пользователи';
	public $addable = true;
	public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'login'   => 'varchar(100)',
        'full_name'   => 'varchar(100)',
        'email'   => 'varchar(100)',
        'password' => 'varchar(128)',
        'hash' => 'varchar(64)',
        'about' => 'varchar(1000)',
        'isadmin' => 'int(1)::0',
        'active' => 'int(1)::1',
        'login_date' => 'datetime',
        'register' => 'timestamp::CURRENT_TIMESTAMP',
        'dateup'    => 'timestamp::CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ];
    public $fillable = ['full_name', 'email', 'password', 'about'];

    public static function login($login = '', $password = '') {
		if(ATTEMPTS) {
			dbi("insert into attempts (type, data, ip) values ('login', '$login, $password', '" . USER_IP . "')");
			$attempts = dbs("select * from attempts where type = 'login' and ip = '" . USER_IP . "'");
			$count_att = count($attempts);
			if($count_att >= 10) {
				dbi("insert into banned_ip (ip) values ('" . USER_IP . "')");
				return [
					'messageText' => 'Заблокированы за перебор паролей',
					'callback' => 'location.href = "' . ROOT . '";'
				];
			}
			if($count_att >= 5) {
				return [
					'messageText' => 'Попробуйте позже или восстановите пароль. Не продолжайте вводить неподходящие данные!'
				];
			}
		}

		$user = dbs("select * from users where login = '$login' and password = '$password' and active = 1 limit 1", true);

		if(!$user) {
			return [
				'message' => 'Неверный логин или пароль'
			];
		}

		session('user_id', $user->id);
		dbu("update users set login_date = '" . dateNow() . "' where id = '$user->id'");
        return [
			'message' => 'Неверный логин или пароль',
			'messageType' => 'success',
			'callback' => 'location.href = "' . ROOT . ($user->isadmin ? 'admin' : 'users') . '";'
		];
   }

   public static function register($login, $password) {
		if(ATTEMPTS) {
			dbi("insert into attempts (type, data, ip) values ('register', '$login, $password', '" . USER_IP . "')");
			$attempts = dbs("select * from attempts where type = 'register' and ip = '" . USER_IP . "'");
			$count_att = count($attempts);
			if($count_att >= 5) {
				return [
					'message' => 'Попробуйте позже'
				];
			}
		}

		$user = dbs("select * from users WHERE login = '$login' and active = 1");

		if($user) {
			return [
				'message' => 'Пользователь существует'
			];
		}
		if(!preg_match('/^[A-z_]{3,50}$/', $login) || strlen($password) < 6) {
			return [
				'message' => 'Проверьте данные'
			];
		}

		$hash = newHash();
		$lid = dbi("insert into users (login, password, hash) VALUES ('$login', '$password', '$hash')");
		return [
			'message' => 'Получилось!',
			'messageType' => 'success',
			'callback' => 'location.href = "' . ROOT . 'users";',
			'user' => Users::getByField('id', $lid)
		];
    }

}