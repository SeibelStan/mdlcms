<?php

class UsersController extends BaseController {

    public static function index() {
        Helpers::guardAuth();
        $fields = Users::getFields(USERID, true);
        $pageTitle = user()->login;
        include(view('users/index'));
    }

    public static function login() {
        if(USERID) {
            redirect(ROOT . 'users');
        }
        $pageTitle = tr('log_in');
        include(view('users/login'));
    }

    public static function doLogin($login = '', $password = '') {
        $result = Users::login($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function register() {
        $pageTitle = tr('sign_up');
        include(view('users/register'));
    }

    public static function doRegister() {
        $result = Users::register($_REQUEST);
        if(isset($result['user'])) {
            Users::login($_REQUEST);
        }

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            redirect(ROOT . 'users');
        }
    }

    public static function remind() {
        $pageTitle = tr('pass_remind');
        include(view('users/remind'));
    }

    public static function doRemind() {
        $result = Users::remind([
            'login' => clearRequest('login')
        ]);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function restore() {
        Users::restore([
            'hash' => clearRequest('hash'),
            'pass' => clearRequest('pass'),
        ]);
        redirect(ROOT);
    }

    public static function logout() {
        session_unset();
        back();
    }

    public static function save() {
        $result = Users::saveProfile($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

}