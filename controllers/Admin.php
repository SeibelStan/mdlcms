<?php

class AdminController {

    public static function index() {
        global $pageTitle;

        Helpers::guardRoles('admin');
        $pageTitle = 'Управление';
        view('admin/index', 'admin');
    }

    public static function table($modelName) {
        global $model;
        global $units;
        global $pagination;
        global $pageTitle;

        Helpers::guardRoles('admin');

        $limit = max(clearRequest('limit'), 50);
        $page = max(clearRequest('page'), 1);
        $sort = clearRequest('sort') ?: "id desc";

        $model = new $modelName();
        $units = $model::getUnits(false, $sort, $limit, $page);
        $pagination = $model::paginate(false, $sort, $limit, $page);

        $pageTitle = $model::getTitle();
        view('admin/table', 'admin');
    }

    public static function editModels() {
        global $modelListExemps;
        global $pageTitle;
        
        Helpers::guardRoles('admin');

        $modelsList = Admin::getModelsList();
        $modelListExemps = [];
        foreach ($modelsList as $model) {
            array_push($modelListExemps, $model);
        }

        $pageTitle = 'Модели';
        view('admin/edit-models', 'admin');
    }

    public static function editUnit($modelName, $id = 0) {
        global $model;
        global $fields;
        global $units;
        global $pageTitle;

        Helpers::guardRoles('admin');

        $model = new $modelName();
        $fields = $model::getFields($id);
        $units = $model::getUnits(false, "id desc");

        $pageTitle = $model::getTitle();
        view('admin/edit-model', 'admin');
    }

    public static function save($modelName, $id = 0) {
        Helpers::guardRoles('admin');
        
        $model = new $modelName();

        if ($result = $model::save($_REQUEST, $id)) {
            $alert = [
                'message' => 'Сохранено',
                'type' => 'success'
            ];
        }
        else {
            $alert = [
                'message' => 'Не сохранено',
            ];
        }

        if (!$id) {
            $alert['callback'] = 'location.href = "' . ROOT . 'admin/edit-models/' . $model::getName() . '/' . $result . '";';
        }

        if (getJS()) {
            echo json_encode($alert);
        }
        else {
            alert($result);
            back();
        }
    }

    public static function delete($modelName, $id = 0) {
        Helpers::guardRoles('admin');

        $model = new $modelName();
        $model::delete('id', $id);
        redirect(ROOT . 'admin/edit-models/' . $model);
    }

    public static function filter($modelName) {
        Helpers::guardRoles('admin');

        $query = request('query');
        $model = new $modelName();
        $result = $model::search($query);

        $options = '';
        foreach ($result as $unit) {
            $options .= '<option data-id="' . $unit->id . '">' . $unit->display_name . '</option>';
        }
        echo $options;
    }

}