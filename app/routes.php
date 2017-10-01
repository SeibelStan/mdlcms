<?php

// Home
$router->map('GET', '/', 'HomeController::index');
$router->map('GET', '/home/feedback', 'HomeController::feedback');
$router->map('POST', '/home/send-feedback', 'HomeController::sendFeedback');
$router->map('GET|POST', '/search', 'HomeController::search');
$router->map('POST', '/search-widget', 'HomeController::searchWidget');

// Catalog
$router->map('GET|POST', '/catalog/[**:url]', 'CatalogController::index', 'catalogUrl');
$router->map('GET|POST', '/catalog', 'CatalogController::index');

// Info
$router->map('GET|POST', '/info/[**:url]', 'InfoController::direct');
$router->map('GET|POST', '/info', 'InfoController::index');

// Users
$router->map('GET', '/users/login', 'UsersController::login');
$router->map('GET|POST', '/users/do-login', 'UsersController::doLogin');
$router->map('GET', '/users/register', 'UsersController::register');
$router->map('GET|POST', '/users/do-register', 'UsersController::doRegister');
$router->map('GET|POST', '/users/logout', 'UsersController::logout');
$router->map('POST', '/users/save', 'UsersController::save');
$router->map('GET', '/users', 'UsersController::index');
$router->map('GET', '/users/[i:id]', 'UsersController::index');
$router->map('POST', '/users/remind', 'UsersController::remind');
$router->map('GET', '/users/restore', 'UsersController::restore');

// Cart
$router->map('GET', '/cart/get', 'CartController::get');
$router->map('POST', '/cart/add', 'CartController::add');
$router->map('GET|POST', '/cart/remove', 'CartController::remove');

// Orders
$router->map('GET', '/orders', 'OrdersController::index');
$router->map('POST', '/orders/create', 'OrdersController::create');

// Files
$router->map('GET|POST', '/admin/files', 'FilesController::index');
$router->map('POST', '/files/upload', 'FilesController::upload');
$router->map('POST', '/files/upload-ckeditor', 'FilesController::uploadCkeditor');
$router->map('GET|POST', '/files/get', 'FilesController::get');
$router->map('GET|POST', '/files/remove', 'FilesController::remove');
$router->map('GET|POST', '/files/rename', 'FilesController::rename');
$router->map('GET|POST', '/files/create-dir', 'FilesController::createDir');

// Admin
$router->map('GET', '/admin', 'AdminController::index');
$router->map('GET', '/admin/edit-models', 'AdminController::editModels');
$router->map('GET', '/admin/edit-models/[a:modelName]', 'AdminController::editUnit');
$router->map('GET', '/admin/edit-models/[a:modelName]/[i:id]', 'AdminController::editUnit');
$router->map('GET|POST', '/admin/save-models/[a:modelName]/[i:id]', 'AdminController::save');
$router->map('GET|POST', '/admin/save-models/[a:modelName]', 'AdminController::save');
$router->map('GET|POST', '/admin/delete-models/[a:modelName]/[i:id]', 'AdminController::delete');
$router->map('GET', '/admin/table/[a:modelName]', 'AdminController::table');

// Helpers
$router->map('POST', '/helpers/friendly-url', 'HelpersController::friendlyUrl');
$router->map('GET', '/lang/[a:lang]', 'HelpersController::setLang');
$router->map('POST', '/helpers/markdown-parse', 'HelpersController::markdownParse');
$router->map('GET', '/r/[a:referal]', 'HelpersController::setReferal');

// Attempts
$router->map('GET', '/' . CRONKEY . '/attempts/reset', 'Attempts::reset');