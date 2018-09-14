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
        $model = new Files(request('dir'));
        echo json_encode($model->upload($_FILES));
    }

    public static function get() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo json_encode($model->get($_FILES));
    }

    public static function delete() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo json_encode($model->delete(request('files'), 1));
    }

    public static function rename() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->rename(request('oldName'), request('newName'));
    }

    public static function dirCreate() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->dirCreate(request('name'));
    }

}