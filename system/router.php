<?php

if (ATTEMPTS) {
    $attempt = Attempts::add('view');
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $attempt->action) {
        echo json_encode($attempt);
    }
}

if (ATTEMPTS && Bans::check()) {
    include(view('errors/403'));
    die();
}

$router = new AltoRouter();
$router->setBasePath(preg_replace('/^\//', '', ROOT));

require('app/routes.php');

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