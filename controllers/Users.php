<?php

class UsersController extends BaseController {

    public static function index() {
        guardAuth();
        $model = new Users();
        $fields = $model->getFields(USERID, true);
        $pageTitle = user()->login;
        include(view('users/index'));
    }

    public static function login() {
        if(USERID) {
            redirect(ROOT . 'users');
        }
        $pageTitle = 'Вход';
        include(view('users/login'));
    }

    public static function doLogin($login = '', $password = '') {
        $model = new Users();
        $result = $model->login($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function register() {
        $pageTitle = 'Регистрация';
        include(view('users/register'));
    }

    public static function doRegister() {
        $model = new Users();
        $result = $model->register($_REQUEST);
        if(isset($result['user'])) {
            $model->login($_REQUEST);
        }

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            redirect(ROOT . 'users');
        }
    }

    public static function logout() {
        session_unset();
        back();
    }

    public static function save() {
        $model = new Users();
        $result = $model->saveProfile($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function remind() {
        $model = new Users();
        $result = $model->remind([
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
        $model = new Users();
        $model->restore([
            'hash' => clearRequest('hash'),
            'pass' => clearRequest('pass'),
        ]);
        redirect(ROOT);
    }

}