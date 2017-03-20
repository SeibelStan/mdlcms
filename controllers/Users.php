<?php

class UsersController extends BaseController {

    public static function index() {
        checkAuth();
        include(view('users/index'));
    }

    public static function login() {
        if(USERID) {
            redirect(ROOT . 'users');
        }
        include(view('users/login'));
    }

    public static function doLogin($login = '', $password = '') {
        $login = $login ?: clearRequest('login', 50);
        $password = clearRequest('password', 128);
        $result = Users::login($login, $password);
        die(json_encode($result));
    }

    public static function register() {
        include(view('users/register'));
    }

    public static function doRegister() {
        $login = clearRequest('login', 50);
        $password = clearRequest('password', 128);

        $result = Users::register($login, $password);
        if(isset($result['user'])) {
            Users::login($login, $password);
        }
        die(json_encode($result));
    }

    public static function logout() {
        session_unset();
        redirect(ROOT . 'users/login');
    }

}