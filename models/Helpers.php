<?php

class Helpers {

    public static function getUser($id = USERID) {
        $id = $id ?: session('user_id');

        $user = Users::getByField('id', $id);
        if (!$user) {
            $user = Users::getByField('login', $id);
        }
        return $user;
    }

    public static function checkRoles($data) {
        if (!user()) {
            return false;
        }
        return array_intersect(
            explode('|', user()->roles),
            explode('|', $data)
        );
    }

    public static function guardAuth() {
        if (!USERID) {
            abort(401);
        }
    }
    public static function guardRoles($data) {
        if (!USERID || !Helpers::checkRoles($data)) {
            abort(401);
        }
    }

    public static function checkAdminZone() {
        $result = false;
        if (preg_match('/admin/', $_SERVER['REQUEST_URI'])) {
            $result = true;
        }
        return $result;
    }

    public static function paginate($count, $limit = 1, $page = 1) {
        if ($count <= $limit) {
            return [];
        }

        $result = [];
        $iPage = 1;
        for ($i = 1; $i <= $count; $i += $limit) {
            $_GET['page'] = $iPage;
            $params = [];
            foreach ($_GET as $k => $v) {
                $params[] = "$k=$v";
            }
            array_push($result, (object) [
                'link' => '?' . implode('&', $params),
                'title' => $iPage,
                'active' => $iPage == $page,
            ]);
            $iPage++;
        }

        return $result;
    }

}