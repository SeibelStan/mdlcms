<?php

class HomeController extends BaseController {

    public static function index() {
        $modelSlider = new Slider();
        $slider = $modelSlider->getByField('name', 'home');
        $modelSlides = new Banners();
        $slides = $modelSlides->getUnits("connect = 'home' and active", "rand()");
        include(view('home/index'));
    }

    public static function feedback() {
        $model = new Feedback();
        $fields = $model->getFields(0, true);
        include(view('home/feedback'));
    }

    public static function sendFeedback() {
        $model = new Feedback();
        $result = $model->send($_REQUEST);
        echo json_encode($result);
    }

    public static function search() {
        $result = A_BaseModel::search(request('searchQuery'), 12);
        include(view('home/search'));
    }

    public static function searchWidget() {
        $result = A_BaseModel::search(request('searchQuery'), 5);
        echo json_encode($result);
    }

}