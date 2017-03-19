<?php

class CatalogController extends BaseController {

    public static function index($url = 0) {
        $urlType = !preg_match('/^\d+$/', $url) ? 'url' : 'id';
        $model = new Catalog();
        $directUnit = $model->getByField($urlType, $url, "and active");
        if($directUnit) {
            $directUnit->url = $directUnit->url ?: $directUnit->id;
        }
        $parentUnit = $directUnit ? $model->getByField('id', $directUnit->connect, "and active") : false;
        if($parentUnit) {
            $parentUnit->url = $parentUnit->url ?: $parentUnit->id;
        }
        $units = $model->getUnits("active and connect = '" . ($directUnit ? $directUnit->id : 0) . "'", "title asc");
        foreach($units as $unit) {
            $unit->url = $unit->url ?: $unit->id;
        }
        include(view('catalog/index'));
    }

}