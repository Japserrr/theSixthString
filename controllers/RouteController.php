<?php

require_once '../controllers/PageController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/RegisterController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/AccountController.php';

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
    case URL_ROOT . '/register':
        register();
        break;
    case URL_ROOT . '/product':
        productShow();
        break;
    case URL_ROOT . '/profile':
        profile();
        break;
    case URL_ROOT . '/edit-info':
        require_once '../views/partials/member-gegevens.php';
        break;
    case URL_ROOT . '/privacy':
        require_once '../views/partials/privacy.php';
        break;
    case URL_ROOT . '/edit-login':
        require_once '../views/partials/member-login.php';
        break;
    case URL_ROOT . '/edit-address':
        require_once '../views/partials/member-address.php';
        break;
    default:
        http_response_code(404);
        require '../views/errors/404.html';
        break;
}
