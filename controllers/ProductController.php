<?php

function productShow(): void
{
    include_once '../models/Product.php';

    // Return when no product id is given
    if (!isset($_GET['product_id'])) {
        // refresh page with javascript because of Cannot modify header information - headers already sent by output" error
        echo '<script>window.location.href = "' . URL_ROOT . '/home";</script>';
        exit();
    }

    if (!is_numeric($_GET['product_id'])) {
        // refresh page with javascript because of Cannot modify header information - headers already sent by output" error
        echo '<script>window.location.href = "' . URL_ROOT . '/404";</script>';
        exit();
    }

    $id = intval($_GET['product_id']);

    $product = (new Product)->get($id);

    $productCategories = (new Product)->getCatagories($id);

    require_once('../views/product/show.phtml');
}
