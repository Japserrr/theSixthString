<?php

include_once '../helpers/database.php';

function homepage(): void
{
    $conn = getDbConnection();
    $sql = "SELECT id, product_name,price FROM product";
    $r = $conn->prepare($sql);
    $r->execute();
    $products = $r->fetchAll();
    $conn = null;

    require_once('../views/home.phtml');
}
