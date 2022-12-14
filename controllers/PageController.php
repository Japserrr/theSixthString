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


function create_account(): void
{



    $auth = ['email' => $_POST['form_email'], 'password' => hash('sha256', $_POST['form_password']), 'active' => 1];

    $user = ['first_name' => $_POST['form_firstname'], 'infix' => $_POST['form_inifx'], 'last_name' => $_POST['form_lastname'], 'phone_number' => $_POST['form_phone']];
    //build object with data from the post

    $address = ['street' => $_POST['form_address'], 'house_number' => $_POST['form_house_number'],  'zip_code' => $_POST['form_zipcode'], 'city' => $_POST['form_city'], 'country' => $_POST['form_country']];


    var_dump($auth);
    $conn = getDbConnection();
    // $sql = 'INSERT INTO auth (email, password, active) VALUES (:email, :password, 1)';
    // $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    // $sth->execute([
    //     'first_name' => 'Timo',
    //     'infix' => 'van',
    //     'last_name' => 'der Laan',
    // ]);
}
function productManagement(): void
{
    require_once('../views/product-management.html');
}
