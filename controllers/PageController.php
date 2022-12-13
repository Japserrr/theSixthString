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


function create_user()
{
    $conn = getDbConnection();

    $sql = 'INSERT INTO user (first_name, infix, last_name) VALUES (:first_name, :infix, :last_name)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([
        'first_name' => 'Timo',
        'infix' => 'van',
        'last_name' => 'der Laan',
    ]);
}
function productManagement(): void
{
    require_once('../views/product-management.html');
}
