<?php

require_once '../controllers/PageController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/RegisterController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/AdminController.php';


require_once '../models/ProductManagement/ProductOverview.php';
require_once '../models/ProductManagement/SearchProduct.php';
require_once '../models/ProductManagement/AddProduct.php';
require_once '../models/ProductManagement/EditProduct.php';
require_once '../models/ProductManagement/DeleteProduct.php';
require_once '../models/ProductManagement/AddBrand.php';
require_once '../models/ProductManagement/AddCategory.php';

$request = explode('?', $_SERVER['REQUEST_URI'])[0];

switch ($request) {

    case URL_ROOT . '/' :
    case URL_ROOT . '' :
    case URL_ROOT . '/home' :
        homepage();
        break;
    case URL_ROOT . '/product-overview':
        productOverview();
        break;
    case URL_ROOT . '/convertionratio':
        convertionRatio();
        break;
    case str_starts_with($request, URL_ROOT . '/product-overview?'):
        searchProduct();
        break;
    case URL_ROOT . '/add-product':
        addProduct();
        break;
    case URL_ROOT . '/edit-product':
        editProduct();
        break;
    case str_starts_with($request, URL_ROOT . '/delete-product?'):
        deleteProduct();
        break;
    case URL_ROOT . '/add-brand':
        addBrand();
        break;
    case URL_ROOT . '/add-category':
        addCategory();
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
    default:
        http_response_code(404);
        require '../views/errors/404.html';
        break;
}
