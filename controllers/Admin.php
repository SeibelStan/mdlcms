<?php

class AdminController extends BaseController {

    public static function index() {
        guardRoles('admin');
        include(view('admin/index'));
    }

    public static function editModels() {
        guardRoles('admin');
        $modelsList = Admin::getModelsList();
        $modelListExemps = [];
        foreach($modelsList as $modelName) {
            array_push($modelListExemps, new $modelName());
        }
        include(view('admin/edit-models'));
    }

    public static function editUnit($modelName, $id = 0) {
        guardRoles('admin');
        $model = new $modelName();
        $fields = $model->getFields($id);
        $units = $model->getUnits(false, "id desc");
        include(view('admin/edit-model'));
    }

    public static function save($modelName, $id = 0) {
        guardRoles('admin');
        $model = new $modelName();
        $model->save($id, $data = $_REQUEST);
        $result = [
            'message' => 'Сохранено',
            'type' => 'success'
        ];
        if(!$id) {
            $result['callback'] = 'location.href = "' . ROOT . 'admin/edit-models/' . $modelName . '";';
        }
        echo json_encode($result);
    }

    public static function delete($modelName, $id = 0) {
        guardRoles('admin');
        $model = new $modelName();
        $model->delete('id', $id);
        redirect(ROOT . 'admin/edit-models/' . $modelName);
    }

}