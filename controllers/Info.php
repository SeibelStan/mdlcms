<?php

class InfoController extends BaseController {

    public static function index() {
        global $router;
        $limit = max(clearRequest('limit'), 12);
        $page = max(clearRequest('page'), 1);
        $sort = clearRequest('sort') ?: "date desc";

        $model = new Info();
        $sql = "active";
        $units = $model->getUnits($sql, $sort, $limit, $page);
        $pagination = $model->paginate($sql, $sort, $limit, $page);

        $units = noimagize($units, 'image');
        $pageTitle = $model->getTitle();
        include(view('info/index'));
    }

    public static function direct($url = '') {
        $model = new Info();
        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $directUnit = $model->getByField($urlType, urldecode($url));
        
        if(!$directUnit) {
            $directUnit = $model->getByField($urlType, urldecode($url) . '-' . getLang());
        }

        if(!$directUnit) {
            abort(404);
        }

        $pageTitle = $directUnit->title;

        include(view('info/direct'));
    }

}