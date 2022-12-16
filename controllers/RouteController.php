<?php
require_once '../controllers/PageController.php';
require_once '../controllers/ProductController.php';

$request = explode('?', $_SERVER['REQUEST_URI'])[0];
switch ($request) {
    case URL_ROOT . '/' :
    case URL_ROOT . '/home' :
    case URL_ROOT . '' :
        home();
        break;
    case URL_ROOT . '/product-management':
        productManagement();
        break;
    case URL_ROOT . '/product':
        productShow();
        break;
    default:
        http_response_code(404);
        require '../views/errors/404.html';
        break;
}
