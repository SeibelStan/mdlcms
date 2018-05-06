<?php

class Admin extends A_BaseModel {

    public static function getModelsList() {
        global $models;
        $modelsList = [];
        foreach($models as $model) {
            if(!isset($model::$table)) {
                continue;
            }
            array_push($modelsList, $model);
        }
        return $modelsList;
    }

}