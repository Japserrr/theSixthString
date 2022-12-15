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

function send_mail($adress)
{
    $to = $adress;

    $subject = 'Uw account is aangemaakt';

    $message = 'Welkom bij de webshop, uw account is aangemaakt.<br>
    Voordat u kunt inloggen met uw emailadres en wachtwoord moet u uw account nog activeren.<br>
    Klik op de onderstaande link om uw account te activeren.<br>
    <a href="http://localhost:8080/activate-account">Activeer uw account</a>';

    mail($to, $subject, $message);
}
function create_account(): void
{

    $conn = getDbConnection();


    $auth = ['email' => $_POST['form_email'], 'password' => hash('sha256', $_POST['form_password']), 'active' => 1];
    $auth_id = insert_auth($conn, $auth);
    $user = ['auth_id' => $auth_id, 'first_name' => $_POST['form_firstname'], 'infix' => $_POST['form_inifx'], 'last_name' => $_POST['form_lastname'], 'phone_number' => $_POST['form_phone']];
    //build object with data from the post
    insert_user($conn, $user);

    $address = ['street_name' => $_POST['form_address'], 'house_number' => $_POST['form_house_number'],  'zipcode' => $_POST['form_zipcode'], 'city' => $_POST['form_city'], 'country' => $_POST['form_country']];

    foreach ($address as $key => $value) {
        if (!empty($value)) {
            $address_id = insert_address($conn, $address);
            insert_uha($conn, $auth_id, $address_id);
            break;
        }
    }
    //send_mail($_POST['form_email']);
    header("Location: ./login");
    exit();
}
function insert_uha($conn, $auth_id, $address_id)
{
    $sql = 'INSERT INTO user_has_address (auth_id, address_id) VALUES (?,?)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$auth_id, $address_id]);
}
function insert_address($conn, $address)
{
    $sql = 'INSERT INTO address (street_name, house_number, zipcode, city, country) VALUES (?,?,?,?,?)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$address['street_name'], $address['house_number'], $address['zipcode'], $address['city'], $address['country']]);
    return $conn->lastInsertId();
}
function insert_auth($conn, $auth)
{

    $sql = 'INSERT INTO auth (email, password, active) VALUES (?,?, 1)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$auth['email'], $auth['password']]);

    return $conn->lastInsertId();
}
function insert_user($conn, $user)
{
    $sql = 'INSERT INTO user (auth_id, first_name, infix, last_name, phone_number) VALUES (?,?,?,?,?)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$user['auth_id'], $user['first_name'], $user['infix'], $user['last_name'], $user['phone_number']]);
}
function productManagement(): void
{
    require_once('../views/product-management.html');
}
