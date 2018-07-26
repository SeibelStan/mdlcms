<?php

class FilesController extends BaseController {

    public static function index() {
        Helpers::guardRoles('admin');
        $filesInModal = false;
        $model = new Files();
        $pageTitle = 'Файлы';
        include(view('admin/files'));
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
        echo json_encode($model->delete(request('files'), request('inUploadPath')));
    }

    public static function rename() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->rename(request('oldName'), request('newName'));
    }

    public static function createDir() {
        Helpers::guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->createDir(request('name'));
    }

}