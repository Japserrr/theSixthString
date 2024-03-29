<?php

require_once '../controllers/PageController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/RegisterController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/LogoutController.php';
require_once '../controllers/AdminPagecontroller.php';
require_once '../controllers/AdminController.php';
require_once '../controllers/CheckoutController.php';
require_once '../controllers/ProductManagementController.php';

$request = explode('?', $_SERVER['REQUEST_URI'])[0];

switch ($request) {
    case URL_ROOT . '/':
    case URL_ROOT . '':
    case URL_ROOT . '/home':
        homepage();
        break;
    case URL_ROOT . '/statistieken':
        convertionRatio();
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
    case URL_ROOT . '/medewerkers':
        if (isset($_POST['id']) && isset($_POST['employee'])) {
            updateEmployee();
            break;
        }
        AdminPage();
        break;
    case URL_ROOT . '/medewerkers/zoeken':
        if (empty($_POST['searchField'])) {
            AdminPage();
            break;
        }
        selectEmployee();
        break;
    case URL_ROOT . '/checkout':
        checkout();
        break;
    case URL_ROOT . '/confirm-payment':
        confirmPayment();
        break;
    case URL_ROOT . '/product-management':
        (new ProductManagementController)->productManagement();
        break;
    default:
        http_response_code(404);
        require '../views/errors/404.html';
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
}
