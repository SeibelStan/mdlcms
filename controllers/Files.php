<?php

class FilesController extends BaseController {

    public static function index() {
        guardRoles('admin');
        $filesInModal = false;
        $model = new Files();
        $pageTitle = $model->getTitle();
        include(view('admin/files'));
    }

    public static function upload() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->upload($_FILES);
    }

    public static function get() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->get($_FILES);
    }

    public static function delete() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->delete(request('files'), request('inUploadPath'));
    }

    public static function rename() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->rename(request('oldName'), request('newName'));
    }

    public static function createDir() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->createDir(request('name'));
    }

}