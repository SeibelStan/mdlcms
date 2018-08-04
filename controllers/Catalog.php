<?php

class CatalogController {

    public static function index($arg = '') {
        global $router;
        $model = 'catalog';
        $limit = max(request('limit'), 12);
        $page = max(request('page'), 1);
        $sort = request('sort') ?: "title asc";

        $routeName = $router->match()['name'];
        $pageCond = !preg_match('/Url/', $routeName);

        $url = $pageCond ? '' : $arg;
        $page = $pageCond ? $page : 1;

        $directUnit = false;
        $pagination = false;

        $pageTitle = $model::getTitle();
        if($url) {
            $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
            $directUnit = $model::getByField($urlType, urldecode($url), "and active");
            if(!$directUnit) {
                abort(404);
            }
            $directUnit = noimagize($directUnit);
            $pageTitle = $directUnit->title;
        }

        $parentUnit = $directUnit && $directUnit->connect ? $model::getByField('id', $directUnit->connect, "or url = '$directUnit->connect' and active") : false;
        $connectId = $directUnit ? $directUnit->id : 0;
        $connectUrl = $directUnit ? $directUnit->url : '';

        $sql = "active and connect in('$connectId', '$connectUrl')";
        $units = $model::getUnits($sql, $sort, $limit, $page);
        $pagination = $model::paginate($sql, $sort, $limit, $page);

        $units = noimagize($units, 'image');
        include(view('catalog/index'));
    }

}