<?php

require_once '../controllers/PageController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/RegisterController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/LogoutController.php';
require_once '../controllers/AdminController.php';
require_once '../controllers/CheckoutController.php';

$request = explode('?', $_SERVER['REQUEST_URI'])[0];

switch ($request) {
    case URL_ROOT . '/':
    case URL_ROOT . '':
    case URL_ROOT . '/home':
        homepage();
        break;
    case URL_ROOT . '/login':
        login();
        break;
    case URL_ROOT . '/logout':
        logout();
        break;
    case URL_ROOT . '/register':
        register();
        break;
    case URL_ROOT . '/product':
        productShow();
        break;
    case URL_ROOT . '/checkout':
        checkout();
        break;
    case URL_ROOT . '/confirm-payment':
        confirmPayment();
        break;
    default:
        http_response_code(404);
        require '../views/errors/404.html';
        break;
}
