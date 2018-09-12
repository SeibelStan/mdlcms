<?php

class HomeController {

    public static function index() {
        view('home/index', 'main');
    }

    public static function feedback() {
        global $model;
        global $fields;
        global $pageTitle;

        $model = 'feedback';
        $fields = Feedback::getFields(0, true);
        $pageTitle = Feedback::getTitle();
        view('home/feedback', 'main');
    }

    public static function sendFeedback() {
        $result = Feedback::send($_REQUEST);

        if (getJS()) {
            echo json_encode($result);
        }
        else {
            alert($result);
            back();
        }
    }

    public static function search() {
        global $result;
        global $pageTitle;

        $query = request('searchQuery');
        $units = A_BaseModel::search($query, '', 12);
        $pageTitle = $query;
        view('home/search', 'main');
    }

    public static function searchWidget() {
        $query = request('searchQuery');
        $result = A_BaseModel::search($query, '', 5);
        echo json_encode($result);
    }

}