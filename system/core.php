<?php

/* @Request */
function view($view, $layout = false) {
    ob_start();
    require "views/$view.php";
    $viewContent = ob_get_contents();
    ob_clean();
    if ($layout) {
        require "views/layouts/$layout.php";
    }
    else {
        echo $viewContent;
    }
}

function clear($data, $length = 0) {
    $data = strip_tags($data);
    $data = trim($data);
    if ($length) {
        $data = substr($data, 0, $length);
    }
    $data = htmlspecialchars($data);
    return $data;
}

function request($name) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
}

function clearRequest($name, $length = 0) {
    return clear(request($name), $length);
}

function redirect($path) {
    header('Location: ' . $path);
    die();
}

function back() {
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ROOT;
    redirect($referer);
}

function abort($code) {
    if ($code == 404) {
        header('HTTP/1.0 404 Not Found');
    }
    if ($code == 403) {
        header('HTTP/1.0 403 Forbidden');
    }
    if ($code == 401) {
        header('HTTP/1.0 401 Not Authorized');
    }
    session('uri', $_SERVER['REQUEST_URI']);
    view('errors/' . $code, 'main');
    die();
}
/* /Request */

/* @Session */
function session($name, $value = null) {
    if (isset($value)) {
        $_SESSION[$name] = $value;
    }
    return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
}

function alert($data = []) {
    if ($data) {
        $data = (object)$data;
        $result = [
            'message' => @$data->message,
            'type' => @$data->type
        ];
        session('alert', json_encode($result));
    }

    return json_decode(session('alert'));
}

function getJS() {
    return session('js');
}
/* /Session */

/* @DB */
function tableExists($tableName) {
    global $db;
    return $db->query("SHOW TABLES LIKE '$tableName'")->num_rows;
}

function dbEscape($data) {
    global $db;
    return $db->real_escape_string($data);
}

function dbs($sql, $raw = false) {
    global $db;
    $result = $db->query(($raw ? "" : "select * from ") . $sql);
    $data = [];
    if ($result->num_rows) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = (object)$row;
        }
    }
    echo $db->error;
    return $data;
}

function dbi($sql, $raw = false) {
    global $db;
    $db->query(($raw ? "" : "insert into ") . $sql);
    echo $db->error;
    return $db->insert_id;
}

function dbu($sql, $raw = false) {
    global $db;
    $db->query($raw ? "" : "update " . $sql);
    echo $db->error;
    return $db->affected_rows;
}

function dbd($sql, $raw = false) {
    global $db;
    $db->query(($raw ? "" : "delete from ") . $sql);
    echo $db->error;
    return $db->affected_rows;
}
/* /DB */

/* @Array */
function arrayFirst($data) {
    return isset($data[0]) ? $data[0] : false;
}

function textRows($data) {
    return explode("\n", trim($data));
}

function pipeObj($data, $delimiter = '|') {
    $result = (object) [];
    foreach (explode("\n", trim($data)) as $row) {
        $cells = explode($delimiter, $row);
        $k = trim($cells[0]);
        $v = trim(@$cells[1]);
        if (!$k) {
            continue;
        }
        $result->$k = $v;
    }
    return $result;
}

function pipeArr($data, $delimiter = '|') {
    $result = [];
    foreach (explode("\n", trim($data)) as $row) {
        $cells = explode($delimiter, $row);
        $result[] = $cells;
    }
    return $result;
}

function arrayMultiSort($array, $args = []) {
    usort(
        $array, function ($a, $b) use ($args) {
            $res = 0;

            $a = (object)$a;
            $b = (object)$b;

            foreach ($args as $k => $v) {
                if ($a->$k == $b->$k) {
                    continue;
                }

                $res = ($a->$k < $b->$k) ? -1 : 1;
                if ($v == 'desc') {
                    $res = -$res;
                }
                break;
            }

            return $res;
        }
    );

    return $array;
}

function arrayToObject($data) {
    return json_decode(json_encode($data));
}

function object_unshift(&$needle, $haystack) {
    $needle = (array) $needle;
    array_unshift($needle, $haystack);
    return (object) $needle;
}

function shuffle_assoc(&$array) {
    $keys = array_keys($array);
    shuffle($keys);
    $new = [];
    foreach($keys as $key) {
        $new[$key] = $array[$key];
    }
    $array = $new;
    return true;
}
/* /Array */

/* @Mutator */
function stripWord($str, $length, $addon = '...') {
    $str = strip_tags($str);
    if (mb_strlen($str) > $length) {
        $str = mb_substr($str, 0, $length);
        $str = preg_replace('/\S+$/', '', $str);
        $str = preg_replace('/[ !?:.,–-]+$/u', '', trim($str));
        $str .= $addon;
    }
    return $str;
}

function numShorten($num, $prec = 3) {
    $shorts = ['', 'K', 'M', 'B', 'T', 'Qa', 'Qi'];
    foreach ($shorts as $i => $sh) {
        $div = pow(1000, $i);
        if (abs($num) < ($div * 1000)) {
            return number_format($num / $div, $prec) . $sh;
        }
    }
}

function doubleDig($i) {
    return ($i < 10) ? '0' . $i : $i;
}

function dateReformat($date, $format = 'd.m.y H:i') {
    return date($format, strtotime($date));
}

function now() {
    return date('Y-m-d H:i:s');
}

function noimagize($data, $prop = 'image') {
    $noImg = ROOT . 'assets/img/noimage.jpg';

    if (gettype($data) == 'array') {
        foreach ($data as &$unit) {
            if (!($unit->$prop && file_exists($unit->$prop))) {
                $unit->$prop = $noImg;
            }
        }
    }
    else {
        if (!($data->$prop && file_exists($data->$prop))) {
            $data->$prop = $noImg;
        }
    }
    return $data;
}
/* /Mutator */

/* @Sender */
function smail($title, $text, $to, $from = 'info', $name = SITE_NAME) {
    $from .= '@' . DOMAIN;
    if (!preg_match('/@/', $to)) {
        $to .= '@' . DOMAIN;
    }

    $headers  = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: $name<" . $from . ">\r\n";
    return mail($to, $title, $text, $headers);
}

function curlGetAlong($urls) {
    $mh = curl_multi_init();
    $channels = [];

    foreach ($urls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $channels[$url] = $ch;
    }

    $active = 0;
    do {
        curl_multi_exec($mh, $active);
    } while ($active > 0);

    $result = [];
    foreach ($channels as $channel) {
        $result[] = curl_multi_getcontent($channel);
        curl_multi_remove_handle($mh, $channel);
    }

    curl_multi_close($mh);
    return $result;
}

function curlGetSeq($urls) {
    $result = [];

    foreach ($urls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result[] = curl_exec($ch);
    }

    curl_close($ch);
    return $result;
}
/* /Sender */

/* @i18n */
function getBrowserLang($fallback = 'ru') {
    preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), $matches);
    $langs = array_combine($matches[1], $matches[2]);
    foreach ($langs as $n => $v) {
        $langs[$n] = $v ? $v : 1;
    }
    arsort($langs);
    $lang = key($langs);
    $lang = preg_replace('/-.+/', '', $lang);
    return file_exists('data/i18n/' . $lang . '.json') ? $lang : $fallback;
}

function tr($data, $fallback = true) {
    $i18n = pipeObj(file_get_contents('data/i18n/' . getLang() . '.txt'));
    if (isset($i18n->$data)) {
        return $i18n->$data;
    }
    elseif ($fallback) {
        $i18n = pipeObj(file_get_contents('data/i18n/en.txt'));
        if (isset($i18n->$data)) {
            return $i18n->$data;
        }
    }
    return $data;
}

function getLang() {
    return session('lang');
}
/* /i18n */

/* @Other */
function hashGen($length = 64) {
    $hash = base64_encode(md5(time()));
    if ($length) {
        $hash = substr($hash, 0, $length);
    }
    return $hash;
}

function user($id = USERID) {
    return Helpers::getUser($id);
}

function assetTime() {
    return '?v=' . (DEBUG ? time() : date('Y-m-d'));
}

function errorHandler($num, $type, $file, $line, $context = null) {
    global $ERRORS;
    $ERRORS .= $_SERVER['REQUEST_URI'] . ", " . USERID . "<br>$type $file #$line<br><br>";
}