<?php

require_once 'Common.php';

function searchProduct(): void
{
    isAuthorized();

    if (empty($_GET['search'])) {
        header('Location: ' . URL_ROOT . '/product-overview');
        return;
    }
    $search = $_GET['search'];
    unset($_GET['search']);

    $data['products'] = findProducts($search);
    printJsonData($data);

    echo '<script type="text/javascript" src="./public/js/ProductManagement/ProductManagement.js"></script>';

    require_once '../views/ProductManagement/product-overview.html';
}
