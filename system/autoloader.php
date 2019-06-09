<?php

$autoloadDirs = [
    'models/',
    'controllers/'
];

$MODELS = [];

foreach ($autoloadDirs as $dir) {
    $autoloadFiles = scandir($dir);
    $autoloadFiles = array_splice($autoloadFiles, 2);
    foreach ($autoloadFiles as $file) {
        if ($dir == 'models/') {
            $model = preg_replace('/\.php/', '', $file);
            array_push($MODELS, $model);
        }
        require $dir . $file;
    }
}