<?php

class UsersController {

    public static function index() {
        global $model;
        global $fields;
        global $pageTitle;

        Helpers::guardAuth();
        $model = new Users();
        $fields = $model::getFields(USERID, true);
        $pageTitle = user()->login;
        view('users/index', 'main');
    }

    public static function login() {
        global $pageTitle;

        if (USERID) {
            redirect(ROOT . 'users');
        }
        $pageTitle = tr('log_in');
        view('users/login', 'main');
    }

    public static function register() {
        global $pageTitle;

        $pageTitle = tr('sign_up');
        view('users/register', 'main');
    }

    public static function remind() {
        global $pageTitle;
        
        $pageTitle = tr('pass_remind');
        view('users/remind', 'main');
    }

    public static function restore() {
        Users::restore([
            'hash' => clearRequest('hash'),
            'pass' => clearRequest('pass'),
        ]);
        redirect(ROOT);
    }

    public static function doLogin($login = '', $password = '') {
        $result = Users::login($_REQUEST);

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

    public static function doRegister() {
        $result = Users::register($_REQUEST);
        if (isset($result['user'])) {
            Users::login($_REQUEST);
        }

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            redirect(ROOT . 'users');
        }
    }

    public static function doRemind() {
        $result = Users::remind([
            'login' => clearRequest('login')
        ]);

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

    public static function logout() {
        session_unset();
        back();
    }

    public static function save() {
        $result = Users::saveProfile($_REQUEST);

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

}