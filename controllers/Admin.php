<?php

class AdminController extends BaseController {

    public static function index() {
        checkAdmin();
        include(view('admin/index'));
    }

    public static function editModels() {
        checkAdmin();
        global $models;
        $modelsList = Admin::getModelsList();
        $modelListExemps = [];
        foreach($modelsList as $modelName) {
            array_push($modelListExemps, new $modelName());
        }
        include(view('admin/edit-models'));
    }

    public static function editUnit($modelName, $id = 0) {
        checkAdmin();
        $model = new $modelName();
        $fields = $model->getFields($id);
        $units = $model->getUnits(false, "id desc");
        include(view('admin/edit-model'));
    }

    public static function saveUnit($modelName, $id = 0) {
        checkAdmin();
        $model = new $modelName();
        $model->saveUnit($id, $data = $_REQUEST);
        $result = [
            'message' => 'Сохранено',
            'messageType' => 'success'
        ];
        if(!$id) {
            $result['callback'] = 'location.href = "' . ROOT . 'admin/edit-models/' . $modelName . '";';
        }
        die(json_encode($result));
    }

    public static function deleteUnit($modelName, $id = 0) {
        checkAdmin();
        $model = new $modelName();
        $model->deleteUnit('id', $id);
        redirect(ROOT . 'admin/edit-model/' . $modelName);
    }

}