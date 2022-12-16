<?php


function productShow(): void
{
    include_once '../models/Product.php';

    // Return when no product id is given
    if (!isset($_GET['product_id'])) {
        header("Location: " . URL_ROOT . "/home");
    }

    if (!is_numeric($_GET['product_id'])) {
        header("Location: " . URL_ROOT . "/404");
    }

    $id = intval($_GET['product_id']);

    $product = (new Product)->get($id);

    require_once('../views/product/show.php');
}
