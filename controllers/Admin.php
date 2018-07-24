<?php

class AdminController extends BaseController {

    public static function index() {
        Helpers::guardRoles('admin');
        $pageTitle = 'Управление';
        include(view('admin/index'));
    }

    public static function table($model) {
        Helpers::guardRoles('admin');
        $limit = max(clearRequest('limit'), 50);
        $page = max(clearRequest('page'), 1);
        $sort = clearRequest('sort') ?: "id desc";

        $units = $model::getUnits(false, $sort, $limit, $page);
        $pagination = $model::paginate(false, $sort, $limit, $page);

        $pageTitle = $model::getTitle();
        include(view('admin/table'));
    }

    public static function editModels() {
        Helpers::guardRoles('admin');
        $modelsList = Admin::getModelsList();
        $modelListExemps = [];
        foreach($modelsList as $model) {
            array_push($modelListExemps, $model);
        }
        $pageTitle = 'Модели';
        include(view('admin/edit-models'));
    }

    public static function editUnit($model, $id = 0) {
        Helpers::guardRoles('admin');
        $fields = $model::getFields($id);
        $units = $model::getUnits(false, "id desc");
        $pageTitle = $model::getTitle();
        include(view('admin/edit-model'));
    }

    public static function save($model, $id = 0) {
        Helpers::guardRoles('admin');
        $model::save($data = $_REQUEST, $id);
        $result = [
            'message' => 'Сохранено',
            'type' => 'success'
        ];
        if(!$id) {
            $result['callback'] = 'location.href = "' . ROOT . 'admin/edit-models/' . $model . '";';
        }

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function delete($model, $id = 0) {
        Helpers::guardRoles('admin');
        $model::delete('id', $id);
        redirect(ROOT . 'admin/edit-models/' . $model);
    }

    public static function filter($model) {
        Helpers::guardRoles('admin');
        $query = request('query');
        $result = $model::search($query);
        $options = '';
        foreach($result as $unit) {
            $options .= '<option data-id="' . $unit->id . '">' . $unit->display_name . '</option>';
        }
        echo $options;
    }

}