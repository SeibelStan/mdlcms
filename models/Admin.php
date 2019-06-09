<?php

class Admin extends A_BaseModel {

    public static function getModelsList() {
        global $MODELS;
        $modelsList = [];
        foreach ($MODELS as $model) {
            if (!isset($model::$table)) {
                continue;
            }
            array_push($modelsList, $model);
        }
        return $modelsList;
    }

}