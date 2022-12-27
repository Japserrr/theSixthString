<?php

include_once '../helpers/database.php';
include_once '../helpers/validate.php';
function homepage(): void
{
    var_dump($_SESSION);
    $conn = getDbConnection();
    $sql = "SELECT id, product_name,price FROM product";
    $r = $conn->prepare($sql);
    $r->execute();
    $products = $r->fetchAll();
    $conn = null;

    require_once('../views/home.phtml');
}


