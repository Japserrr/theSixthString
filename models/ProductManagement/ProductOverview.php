<?php

require_once 'Common.php';

function productOverview(?array $notification = []): void
{
    isAuthorized();

    $data['products'] = findProducts();

    if (!empty($notification)) {
        $data['notification'] = $notification;
    }

    printJsonData($data);

    echo '<script type="text/javascript" src="./public/js/ProductManagement/ProductManagement.js"></script>';

    require_once '../views/ProductManagement/product-overview.html';
}