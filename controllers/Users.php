<?php

class UsersController extends BaseController {

    public static function index() {
        guardAuth();
        $model = new Users();
        $fields = $model->getFields(USERID, true);
        include(view('users/index'));
    }

    public static function login() {
        if(USERID) {
            redirect(ROOT . 'users');
        }
        include(view('users/login'));
    }

    public static function doLogin($login = '', $password = '') {
        $result = Users::login($_REQUEST);
        echo json_encode($result);
    }

    public static function register() {
        include(view('users/register'));
    }

    public static function doRegister() {
        $model = new Users();
        $result = $model->register($_REQUEST);
        if(isset($result['user'])) {
            $model->login($_REQUEST);
        }
        echo json_encode($result);
    }

    public static function logout() {
        session_unset();
        redirect(ROOT . 'users/login');
    }

    public static function save() {
        $model = new Users();
        $model->save(USERID, $_REQUEST, true);
        echo json_encode([
            'message' => 'Сохранено',
            'type' => 'success'
        ]);
    }

}