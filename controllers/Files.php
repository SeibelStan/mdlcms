<?php

class FilesController extends BaseController {

    public static function index() {
        guardRoles('admin');
        $filesInModal = false;
        include(view('admin/files'));
    }

    public static function upload() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->upload($_FILES);
    }

    public static function uploadCkeditor() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->uploadCkeditor($_FILES);
    }

    public static function get() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->get($_FILES);
    }

    public static function remove() {
        guardRoles('admin');
        $model = new Files(request('dir'));
        echo $model->remove(request('files'), request('inUploadPath'));
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