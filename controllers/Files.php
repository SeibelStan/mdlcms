<?php

class FilesController extends BaseController {

    public static function index() {
        checkAdmin();
        $filesInPage = true;
        include(view('admin/files'));
    }

    public static function upload() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->upload($_FILES);
    }

    public static function uploadCkeditor() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->uploadCkeditor($_FILES);
    }

    public static function get() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->get($_FILES);
    }

    public static function remove() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->remove(request('files'));
    }

    public static function rename() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->rename(request('oldName'), request('newName'));
    }

    public static function createDir() {
        checkAdmin();
        $model = new Files(request('dir'));
        echo $model->createDir(request('name'));
    }

}