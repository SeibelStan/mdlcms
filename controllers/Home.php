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
        $pageTitle = $model->getTitle();
        include(view('home/feedback'));
    }

    public static function sendFeedback() {
        $model = new Feedback();
        $result = $model->send($_REQUEST);
        
        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function search() {
        $model = new A_BaseModel();
        $result = $model->search(request('searchQuery'), '', 12);
        $pageTitle = $model->getTitle();
        include(view('home/search'));
    }

    public static function searchWidget() {
        $model = new A_BaseModel();
        $result = $model->search(request('searchQuery'), '', 5);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            include(view('home/search'));
        }
    }

}