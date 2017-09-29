<?php

$autoload_dirs = [
    'models/',
    'controllers/'
];

$models = [];

foreach($autoload_dirs as $dir) {
    $autoload_files = scandir($dir);
    $autoload_files = delDots($autoload_files);
    foreach($autoload_files as $file) {
        if($dir == 'models/') {
            $model = preg_replace('/\.php/', '', $file);
            array_push($models, $model);
        }
        require($dir . $file);
    }
}