<?php

class NewsController extends BaseController {

    public static function index($arg = '') {
        global $router;
        $limit = 12;

        $routeName = $router->match()['name'];
        $pageCond = !preg_match('/Url/', $routeName);

        $url = $pageCond ? '' : $arg;
        $page = $pageCond ? max($arg, 1) : 1;

        $model = new News();
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
        }
        else {
            $units = $model->getUnits($sql, "date desc", $limit, $page);
            $pagination = $model->paginate($sql, $limit, $page);
        }
        include(view('news/index'));
    }

}