<?php

$attempt = Attempts::add('view');
if($attempt->action) {
    echo json_encode($attempt);
}

if(ATTEMPTS && Bans::check()) {
    include(view('errors/403'));
    die();
}

if(SSL_ENABLED && $_SERVER['SERVER_PORT'] == 80) {
    redirect(SITE_DOMAIN . $_SERVER['REQUESR_URI']);
}

$router = new AltoRouter();
$router->setBasePath(preg_replace('/^\//', '', ROOT));

require('app/routes.php');

$match = $router->match();

if($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']); 
}
else {
    abort(404);
}