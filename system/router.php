<?php

if(SITE_DOMAIN_SSL && ENV == 'prod' && $_SERVER['SERVER_PORT'] == 80) {
    redirect(SITE_DOMAIN_SSL . $_SERVER['REQUESR_URI']);
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