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
        $data = $_REQUEST;
        $result = $model->send($data);
        if(isset($result['messageType']) && $result['messageType'] == 'success') {
            $model = new Feedback();
            $model->saveUnit(0, $data, true);
        }
        die(json_encode($result));
    }

}