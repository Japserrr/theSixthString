<?php
require_once '../controllers/PageController.php';

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case URL_ROOT . '/' :
    case URL_ROOT . '/home' :
    case URL_ROOT . '' :
        home();
        break;
    case URL_ROOT . '/product-management':
        productManagement();
        break;
    default:
        http_response_code(404);
        require '../views/errors/404.html';
        break;
}
