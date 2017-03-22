<?php

// Home
$router->map('GET', '/', 'HomeController::index');
$router->map('GET', '/home/feedback', 'HomeController::feedback');
$router->map('GET|POST', '/home/send-feedback', 'HomeController::sendFeedback');

// Catalog
$router->map('GET|POST', '/catalog/[**:url]', 'CatalogController::index');
$router->map('GET|POST', '/catalog', 'CatalogController::index');

// News
$router->map('GET|POST', '/news/[**:url]', 'NewsController::index');
$router->map('GET|POST', '/news', 'NewsController::index');

// Users
$router->map('GET', '/users/login', 'UsersController::login');
$router->map('GET', '/login', 'UsersController::login');
$router->map('GET|POST', '/users/do-login', 'UsersController::doLogin');
$router->map('GET', '/users/register', 'UsersController::register');
$router->map('GET', '/register', 'UsersController::register');
$router->map('GET|POST', '/users/do-register', 'UsersController::doRegister');
$router->map('GET|POST', '/users/logout', 'UsersController::logout');
$router->map('GET', '/users', 'UsersController::index');
$router->map('GET', '/users/[i:id]', 'UsersController::index');

// Cart
$router->map('GET', '/cart/get', 'CartController::get');
$router->map('POST', '/cart/add', 'CartController::add');
$router->map('GET|POST', '/cart/remove', 'CartController::remove');

// Orders
$router->map('GET', '/orders', 'OrdersController::index');
$router->map('POST', '/orders/add', 'OrdersController::create');

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
$router->map('GET|POST', '/admin/save-models/[a:modelName]/[i:id]', 'AdminController::saveUnit');
$router->map('GET|POST', '/admin/save-models/[a:modelName]', 'AdminController::saveUnit');
$router->map('GET|POST', '/admin/delete-models/[a:modelName]/[i:id]', 'AdminController::deleteUnit');

// Cron
$router->map('GET', '/cron-key/reset-attempts/[a:type]', 'A_BaseModel::resetAttemts');
$router->map('GET', '/cron-key/reset-attempts', 'A_BaseModel::resetAttemts');