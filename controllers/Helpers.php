<?php

class HelpersController extends BaseController {

    public static function resetAttemts($type = '') {
        $where = $type ? "where type = '$type'" : '';
        echo dbu("delete from attempts $where");
        back();
    }

    public static function friendlyUrl() {
        die(friendlyUrl(request('url')));
    }

}