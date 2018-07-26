<?php

class HomeController extends BaseController {

    public static function index() {
        include(view('home/index'));
    }

    public static function feedback() {
        $model = 'feedback';
        $fields = Feedback::getFields(0, true);
        $pageTitle = Feedback::getTitle();
        include(view('home/feedback'));
    }

    public static function sendFeedback() {
        $result = Feedback::send($_REQUEST);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            back();
        }
    }

    public static function search() {
        $query = request('searchQuery');
        $result = A_BaseModel::search($query, '', 12);
        $pageTitle = $query;
        include(view('home/search'));
    }

    public static function searchWidget() {
        $query = request('searchQuery');
        $result = A_BaseModel::search($query, '', 5);

        if(getJS()) {
            echo json_encode($result);
        }
        else {
            setResAlert($result);
            include(view('home/search'));
        }
    }

}