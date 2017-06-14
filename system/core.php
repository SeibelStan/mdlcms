<?php

function view($page) {
    return 'views/' . $page . '.php';
}

function clear($data, $length) {
    $data = strip_tags($data);
    $data = trim($data);
    if($length) {
        $data = substr($data, 0, $length);
    }
    $data = htmlspecialchars($data);
    return $data;
}

function dbEscape($data) {
    global $db;
    return $db->real_escape_string($data);
}

function textRows($data) {
    return explode("\n", trim($data));
}

function request($name) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
}
function clearRequest($name, $length) {
    return clear(request($name), $length);
}

function session($name, $value = null) {
    if(isset($value)) {
        $_SESSION[$name] = $value;
    }
    return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
}

function redirect($path) {
    header('Location: ' . $path);
    die();
}

function back() {
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ROOT;
    redirect($referer);
}

function abort($err) {
    session('uri', $_SERVER['REQUEST_URI']);
    include(view('errors/' . $err));
    die();
}

function guardAuth() {
    if(!USERID) {
        abort(401);
    }
}
function guardRoles($data) {
    if(!USERID || !Helpers::checkRoles($data)) {
        abort(401);
    }
}

function checkAdminZone() {
    $result = false;
    if(preg_match('/admin/', $_SERVER['REQUEST_URI'])) {
        $result = true;
    }
    return $result;
}

function dbs($sql, $single = false) {
    global $db;
    $result = $db->query($sql);
    $data = [];
    if($result->num_rows) {
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = (object)$row;
        }
    }
    echo $db->error;
    return $single ? (isset($data[0]) ? $data[0] : []) : $data;
}
function dbi($sql) {
    global $db;
    $db->query($sql);
    echo $db->error;
    return $db->insert_id;
}
function dbu($sql) {
    global $db;
    $db->query($sql);
    echo $db->error;
    return $db->affected_rows;
}
function arrayFirst($data) {
    return isset($data[0]) ? $data[0] : false;
}

function stripWord($str, $length, $addon = '...') {
    $str = strip_tags($str);
    if(mb_strlen($str) > $length) {
        $str = mb_substr($str, 0, $length);
        $str = preg_replace('/\S+$/', '', $str);
        $str = preg_replace('/[ !?:.,–-]+$/', '', trim($str));
        $str .= $addon;
    }
    return $str;
}

function doubleDig($i) {
    return ($i < 10) ? '0' . $i : $i;
}

function delDotsFilter($name) {
    return !preg_match('/^\.{1,2}$/', $name);
}
function delDots($arr) {
    return array_filter($arr, 'delDotsFilter');
}

function dateStrafe($period, $date = false) {
    $date = $date ?: date('Y-m-d');
    return date('Y-m-d H:i:s', strtotime($date . ' ' . $period));
}
function dateNow($forDB = false) {
    $strafe = $forDB ? DB_TIME_DELTA : 0;
    return dateStrafe('+' . $strafe . ' seconds', date('Y-m-d'));
}
function dateNowFull($forDB = false) {
    $strafe = $forDB ? DB_TIME_DELTA : 0;
    return dateStrafe('+' . $strafe . ' seconds', date('Y-m-d H:i:s'));
}
function dateReformat($date, $format = 'd.m.y H:i') {
    return date($format, strtotime($date));
}

function passGen($length = 16) {
    $pass =  base64_encode(md5(time()));
    if($length) {
        $pass = substr($pass, 0, $length);
    }
    return $pass;
}
function newHash() {
    return passGen(64);
}

function tableExists($tableName) {
    global $db;
    $like = addcslashes($tableName, '%_\\');
    $result = $db->query(
        "SHOW TABLES LIKE '" . $db->real_escape_string($like) . "';"
    );
    $found = $result->num_rows > 0;
    return $found;
}

function translit($data) {
    $rules = [
        'а' => 'a',  'б' => 'b',  'в' => 'v',
        'г' => 'g',  'д' => 'd',  'е' => 'e',
        'ё' => 'e',  'ж' => 'zh', 'з' => 'z',
        'и' => 'i',  'й' => 'j',  'к' => 'k',
        'л' => 'l',  'м' => 'm',  'н' => 'n',
        'о' => 'o',  'п' => 'p',  'р' => 'r',
        'с' => 's',  'т' => 't',  'у' => 'u',
        'ф' => 'f',  'х' => 'h',  'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ъ' => '',   'ы' => 'y',  'ь' => '',
        'э' => 'e',  'ю' => 'ju', 'я' => 'ja',

        'А' => 'A',  'Б' => 'B',  'В' => 'V',
        'Г' => 'G',  'Д' => 'D',  'Е' => 'E',
        'Ё' => 'E',  'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I',  'Й' => 'J',  'К' => 'K',
        'Л' => 'L',  'М' => 'M',  'Н' => 'N',
        'О' => 'O',  'П' => 'P',  'Р' => 'R',
        'С' => 'S',  'Т' => 'T',  'У' => 'U',
        'Ф' => 'F',  'Х' => 'H',  'Ц' => 'C',
        'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'sch',
        'Ъ' => '',   'Ы' => 'Y',  'Ь' => '',
        'Э' => 'E',  'Ю' => 'JU', 'Я' => 'Ja',
    ];
    mb_internal_encoding("UTF-8");
    return strtr($data, $rules);
}

function friendlyUrl($data) {
    $data = mb_strtolower($data);
    $data = preg_replace('/[!?:;\'",.<>() ]/i', '-', $data);
    $data = preg_replace('/-+/', '-', $data);
    return translit($data);
}

function assetTime() {
    return '?v=' . (DEBUG ? time() : date('Y-m-d'));
}

function jsLog($data) {
    echo '<script>console.log("' . $data . '");</script>';
}

function getLang() {
    return session('lang');
}

function isSequre() {
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
}

function exportToCsv($table, $fields, $filename = 'export.csv') {
    global $db;
    $sql_query = "SELECT {$fields} FROM {$table}";
 
    $result = $db->query($sql_query);

    $f = fopen('php://temp', 'wt');
    $first = true;
    while($row = $result->fetch_assoc()) {
        if ($first) {
            fputcsv($f, array_keys($row));
            $first = false;
        }
        fputcsv($f, $row);
    }

    $size = ftell($f);
    rewind($f);

    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $size");
    header("Content-type: text/x-csv");
    header("Content-type: text/csv");
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename");
    fpassthru($f);
    exit;
}