<?php
function send_mail($adress)
{
    $to = $adress;

    $subject = 'Uw account is aangemaakt';

    $message = 'Welkom bij the sixth string, uw account is aangemaakt.<br>
    Voordat u kunt inloggen met uw emailadres en wachtwoord moet u uw account nog activeren.<br>
    Klik op de onderstaande link om uw account te activeren.<br>
    <a href="http://localhost:8080/activate-account">Activeer uw account</a>';

    mail($to, $subject, $message);
}
//check if email is already in use
function check_email($conn, $email): bool
{
    //build query
    $sql = 'SELECT email FROM auth WHERE email = ?';
    //prepare statement
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$email]);
    //check if record exists
    if ($sth->rowCount() > 0) {
        return true;
    }

    return false;
}
function register($error = null)
{
    //check if logged in
    if (isLoggedIn()) {
        header('Location: ' . URL_ROOT . '/home');
        exit();
    }
    require_once '../views/login/register.phtml';
}
function create_account()
{
    //check all values for html entities
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlentities($value, ENT_QUOTES, "UTF-8");
    }
    //check if all fields are filled in
    if (empty($_POST['form_email']) || empty($_POST['form_password']) || empty($_POST['form_firstname']) || empty($_POST['form_lastname'])) {
        $errors = [];
        $email = empty($_POST['form_email']) ? "Email is verplicht" : "";
        $password = empty($_POST['form_password']) ? "Wachtwoord is verplicht" : "";
        $firstname = empty($_POST['form_firstname']) ? "Voornaam is verplicht" : "";
        $lastname = empty($_POST['form_lastname']) ? "Achternaam is verplicht" : "";
        array_push($errors, ["email" => $email, "password" => $password, "firstname" => $firstname, "lastname" => $lastname]);
        register($errors,  $_POST);
        exit();
    }
    //get connection
    $conn = getDbConnection();


    //check if email is already in use
    if (check_email($conn, $_POST['form_email'])) {
        return [
            "email" =>  "Email is al in gebruik"
        ];
    }

    //insert auth record
    $auth_id = insert_auth($conn, ['email' => $_POST['form_email'], 'password' => password_hash($_POST['form_password'], PASSWORD_BCRYPT), 'active' => 1]);
    //build object with data from the post
    insert_user($conn, ['auth_id' => $auth_id, 'first_name' => $_POST['form_firstname'], 'infix' => $_POST['form_inifx'], 'last_name' => $_POST['form_lastname'], 'phone_number' => $_POST['form_phone']]);

    //build address object, and check if country is set
    $address = [
        'street_name' => $_POST['form_address'], 'house_number' => $_POST['form_house_number'],
        'zipcode' => $_POST['form_zipcode'], 'city' => $_POST['form_city'],
        'country' =>  array_key_exists("form_country", $_POST) ? $_POST['form_country'] : ""
    ];
    //check if any of the optional fields are filled in, if not then skip insertion of address and uha
    foreach ($address as $key => $value) {
        if (!empty($value)) {
            $address_id = insert_address($conn, $address);
            insert_uha($conn, $auth_id, $address_id);
            break;
        }
    }
    //send_mail($_POST['form_email']);
    create_session($auth_id, false);
    //navitage to homepage
    echo '<script>window.location.href = "' . URL_ROOT . '/home";</script>';
    exit();
}

/// Inserts a new link between user and address into the database
function insert_uha($conn, $auth_id, $address_id)
{
    //build query and prepare statement
    $sth = $conn->prepare('INSERT INTO user_has_address (auth_id, address_id) VALUES (?,?)', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$auth_id, $address_id]);
}
/// Inserts a new address into the database
function insert_address($conn, $address): int
{
    //build query and prepare statement
    $sth = $conn->prepare('INSERT INTO address (street_name, house_number, zipcode, city, country) VALUES (?,?,?,?,?)', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$address['street_name'], $address['house_number'], $address['zipcode'], $address['city'], $address['country']]);
    //return last inserted id
    return $conn->lastInsertId();
}
/// Inserts new login credentials into the database
function insert_auth($conn, $auth): int
{
    //build query and prepare statement
    $sth = $conn->prepare('INSERT INTO auth (email, password, active, created_at) VALUES (?,?, 1, NOW())', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$auth['email'], $auth['password']]);
    //return last inserted id
    return $conn->lastInsertId();
}

/// Inserts a new user into the database
function insert_user($conn, $user)
{
    //build query and prepare statement
    $sth = $conn->prepare('INSERT INTO user (auth_id, first_name, infix, last_name, phone_number, employee) VALUES (?,?,?,?,?, 0)', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$user['auth_id'], $user['first_name'], $user['infix'], $user['last_name'],  $user['phone_number']]);
}
