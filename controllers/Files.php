<?php

class FilesController {

    public static function index() {
        global $filesInModal;
        global $pageTitle;

        Helpers::guardRoles('admin');
        $filesInModal = false;
        $model = new Files();
        $pageTitle = 'Файлы';
        view('admin/files', 'admin');
    }

    public static function upload() {
        Helpers::guardRoles('admin');
        $dir = clearRequest('dir');
        $model = new Files($dir);
        echo json_encode($model->upload($_FILES['files']));
    }

    public static function get() {
        Helpers::guardRoles('admin');
        $dir = clearRequest('dir');
        $model = new Files($dir);
        echo json_encode($model->get());
    }

    public static function delete() {
        Helpers::guardRoles('admin');
        $dir = clearRequest('dir');
        $model = new Files($dir);
        echo json_encode($model->delete(request('files'), 1));
    }

    public static function rename() {
        Helpers::guardRoles('admin');
        $dir = clearRequest('dir');
        $model = new Files($dir);
        echo $model->rename(clearRequest('oldName'), clearRequest('newName'));
    }

    public static function dirCreate() {
        Helpers::guardRoles('admin');
        $dir = clearRequest('dir');
        $model = new Files($dir);
        echo $model->dirCreate(clearRequest('name'));
    }

}