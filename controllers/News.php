<?php

class NewsController extends BaseController {

    public static function index($url = 0) {
        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $model = new News();
        $directUnit = $model->getByField($urlType, urldecode($url), "and active");
        if($directUnit) {
            $directUnit->url = $directUnit->url ?: $directUnit->id;
            $units = false;
        }
        else {
            $units = $model->getUnits("active", "date desc");
            foreach($units as $unit) {
                $unit->url = $unit->url ?: $unit->id;
            }
        }
        include(view('news/index'));
    }

}