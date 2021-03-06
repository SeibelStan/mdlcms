<?php

if (ATTEMPTS) {
    $attempt = Attempts::add('view');
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $attempt->reason) {
        echo json_encode($attempt);
    }
}

if (ATTEMPTS && Bans::check()) {
    view('errors/403', 'main');
    die();
}

$router = new AltoRouter();
$router->setBasePath(preg_replace('/^\//', '', ROOT));

require 'app/routes.php';

$match = $router->match();

if ($match && is_callable($match['target'])) {
    if (SSL && $_SERVER['REQUEST_SCHEME'] == 'http') {
        redirect(FULLHOST . $_SERVER['REQUEST_URI']);
    }
    call_user_func_array($match['target'], $match['params']);
}
else {
    abort(404);
}