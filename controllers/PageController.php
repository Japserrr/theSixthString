<?php

include_once '../helpers/database.php';

function homepage(): void
{
    $values = [];
    $conn = getDbConnection();
    $sql = "SELECT id,product_name,img_path FROM product";
    if (isset($_GET["search"])) {
        $sql .= " WHERE `product_name` LIKE :search";
        $values = ['search' => '%' . $_GET['search'] . '%'];
    }
    $r = $conn->prepare($sql);
    $r->execute($values);
    $products = $r->fetchAll();
    $conn = null;

    require_once('../views/productlist.phtml');
}
