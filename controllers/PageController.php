<?php

include_once '../helpers/database.php';

function home()
{
    // Example Database
    $conn = getDbConnection();

    $sql = 'SELECT first_name, infix, last_name FROM user WHERE first_name LIKE :name LIMIT 1';

    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

    $sth->execute(['name' => 'Timo']);

    $user = $sth->fetchAll()[0] ?? null;

    $conn = null;

    include_once '../views/home.php';
}

function productManagement(): void
{
    require_once('../views/product-management.html');
}
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
