<?php

class NewsController extends BaseController {

    public static function index($url = 0) {
        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $model = new News();
        $directUnit = $model->getByField($urlType, urldecode($url), "and active");
        if($directUnit) {
            $units = false;
            $prevNew = arrayFirst($model->getUnits("static = 0 and id < " . $directUnit->id, "id desc"));
            $nextNew = arrayFirst($model->getUnits("static = 0 and id > " . $directUnit->id, "id asc"));
            if($directUnit->static) {
                $prevNew = false;
                $nextNew = false;
            }
        }
        else {
            $units = $model->getUnits("active and static = 0", "date desc");
        }
        include(view('news/index'));
    }

}