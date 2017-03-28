<?php

class CatalogController extends BaseController {

    public static function index($url = 0) {
        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $model = new Catalog();
        $directUnit = $model->getByField($urlType, urldecode($url), "and active");
        $parentUnit = $directUnit ? $model->getByField('id', $directUnit->connect, "or url = '$directUnit->connect' and active") : false;
        $connectId = $directUnit ? $directUnit->id : 0;
        $connectUrl = $directUnit ? $directUnit->url : '';
        $units = $model->getUnits("active and connect in('$connectId', '$connectUrl')", "title asc", 50);
        include(view('catalog/index'));
    }

}