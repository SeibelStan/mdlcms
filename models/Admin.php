<?php

class Admin extends A_BaseModel {

    public static function getModelsList() {
        global $models;
        $modelsList = [];
        foreach($models as $modelName) {
            $model = new $modelName();
            if(!isset($model->table)) {
                continue;
            }
            array_push($modelsList, $modelName);
        }
        return $modelsList;
    }

}