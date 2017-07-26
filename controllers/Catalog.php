<?php

class CatalogController extends BaseController {

    public static function index($arg = '') {
        global $router;
        $limit = max(request('limit'), 12);
        $page = max(request('page'), 1);
        $sort = request('sort') ?: "date desc";

        $routeName = $router->match()['name'];
        $pageCond = !preg_match('/Url/', $routeName);
        
        $url = $pageCond ? '' : $arg;
        $page = $pageCond ? $page : 1;

        $model = new Catalog();
        $directUnit = false;
        $pagination = false;

        if($url) {
            $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
            $directUnit = $model->getByField($urlType, urldecode($url), "and active");
            $pageTitle = $directUnit->title;
        }
        
        $parentUnit = $directUnit && $directUnit->connect ? $model->getByField('id', $directUnit->connect, "or url = '$directUnit->connect' and active") : false;
        $connectId = $directUnit ? $directUnit->id : 0;
        $connectUrl = $directUnit ? $directUnit->url : '';

        $sql = "active and connect in('$connectId', '$connectUrl')";
        $units = $model->getUnits($sql, "title asc", $limit, $page);
        $pagination = $model->paginate($sql, $limit, $page);
        include(view('catalog/index'));
    }

}