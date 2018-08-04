<?php

$freq = rand(1, 50) == 25;

if ($freq) {
    $sessions = scandir(SESSION_PATH);
    foreach ($sessions as $fname) {
        $file = SESSION_PATH . '/' . $fname;
        $ftime = fileatime($file);
        if (is_file($file)) {
            $ftimeDiff = time() - $ftime;
            if ($ftimeDiff > SESSION_TIME) {
                unlink($file);
            }
        }
    }
}