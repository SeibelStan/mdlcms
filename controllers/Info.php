<?php

class InfoController extends BaseController {

    public static function index($arg = '') {
        global $router;
        $limit = max(clearRequest('limit'), 12);
        $page = max(clearRequest('page'), 1);
        $sort = clearRequest('sort') ?: "date desc";

        $routeName = $router->match()['name'];
        $pageCond = !preg_match('/Url/', $routeName);

        $url = $pageCond ? '' : $arg;
        $page = $pageCond ? $page : 1;

        $model = new Info();
        $directUnit = false;
        $pagination = false;

        if($url) {
            $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
            $directUnit = $model->getByField($urlType, urldecode($url), "and active");
        }

        $sql = "active and static = 0";
        if($directUnit) {
            $units = false;
            $prevNew = arrayFirst($model->getUnits($sql . " and id < " . $directUnit->id, "id desc"));
            $nextNew = arrayFirst($model->getUnits($sql . " and id > " . $directUnit->id, "id asc"));
            if($directUnit->static) {
                $prevNew = false;
                $nextNew = false;
            }
            $pageTitle = $directUnit->title;
        }
        else {
            $units = $model->getUnits($sql, $sort, $limit, $page);
            $pagination = $model->paginate($sql, $sort, $limit, $page);
        }
        include(view('info/index'));
    }

}