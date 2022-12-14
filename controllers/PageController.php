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

function product(): void
{
    // Return when no product id is given
    if (!isset($_GET['product_id'])) {
        header("Location: " . URL_ROOT . "/home");
    }

    if (!is_numeric($_GET['product_id'])) {
        header("Location: " . URL_ROOT . "/404");
    }

    $id = intval($_GET['product_id']);

    $conn = getDbConnection();

    $sql = 'SELECT `product_name`, `description`, `price` FROM product WHERE id LIKE :id LIMIT 1';

    $sth = $conn->prepare($sql);
    $sth->execute(['id' => $id]);
    $products = $sth->fetchAll() ?? null;

    $conn = null;

    if (count($products) !== 1) {
        header("Location: " . URL_ROOT . "/404");
    }

    $product = $products[0];

    require_once('../views/product/show.php');
}
