<?php

class InfoController {

    public static function index() {
        global $router;
        global $units;
        global $model;
        global $pagination;
        global $pageTitle;

        $model = 'info';
        $limit = max(clearRequest('limit'), 12);
        $page = max(clearRequest('page'), 1);
        $sort = clearRequest('sort') ?: "date desc";

        $sql = "active";
        $units = $model::getUnits($sql, $sort, $limit, $page);
        $pagination = $model::paginate($sql, $sort, $limit, $page);

        $units = noimagize($units, 'image');
        $pageTitle = $model::getTitle();
        view('info/index', 'main');
    }

    public static function direct($url = '') {
        global $model;
        global $directUnit;
        global $pageTitle;

        $model = 'info';

        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $directUnit = $model::getByField($urlType, urldecode($url));

        if (!$directUnit) {
            $directUnit = $model::getByField($urlType, urldecode($url) . '-' . getLang());
        }

        if (!$directUnit) {
            abort(404);
        }

        $pageTitle = $directUnit->title;

        view('info/direct', 'main');
    }

}