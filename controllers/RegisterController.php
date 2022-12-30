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
function check_email($conn, $email)
{
    $sql = 'SELECT email FROM auth WHERE email = ?';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$email]);
    //check if email is already in use

    if ($sth->rowCount() > 0) {
        return true;
    }

    return false;
}
function register($error = null)
{

    if (isLoggedIn()) {
        header('Location: ' . URL_ROOT . '/home');
        exit();
    }
    require_once '../views/login/register.phtml';
}
function create_account()
{
    if (empty($_POST['form_email']) || empty($_POST['form_password']) || empty($_POST['form_firstname']) || empty($_POST['form_lastname'])) {
        //"email" => "Email is verplicht", "password" => "Wachtwoord is verplicht", "firstname" => "Voornaam is verplicht", "lastname" => "Achternaam is verplicht"
        $errors = [];
        $email = empty($_POST['form_email']) ? "Email is verplicht" : "";
        $password = empty($_POST['form_password']) ? "Wachtwoord is verplicht" : "";
        $firstname = empty($_POST['form_firstname']) ? "Voornaam is verplicht" : "";
        $lastname = empty($_POST['form_lastname']) ? "Achternaam is verplicht" : "";
        array_push($errors, ["email" => $email, "password" => $password, "firstname" => $firstname, "lastname" => $lastname]);
        register($errors,  $_POST);
        exit();
    }
    $conn = getDbConnection();
    if (check_email($conn, $_POST['form_email'])) {
        return [
            "email" =>  "Email is al in gebruik"
        ];
    }

    $auth = ['email' => $_POST['form_email'], 'password' => password_hash($_POST['form_password'], PASSWORD_BCRYPT), 'active' => 1];
    $auth_id = insert_auth($conn, $auth);
    $user = ['auth_id' => $auth_id, 'first_name' => $_POST['form_firstname'], 'infix' => $_POST['form_inifx'], 'last_name' => $_POST['form_lastname'], 'phone_number' => $_POST['form_phone']];
    //build object with data from the post
    insert_user($conn, $user);

    $country = array_key_exists("form_country", $_POST) ? $_POST['form_country'] : "";
    $address = [
        'street_name' => $_POST['form_address'], 'house_number' => $_POST['form_house_number'],
        'zipcode' => $_POST['form_zipcode'], 'city' => $_POST['form_city'], 'country' =>   $country
    ];

    foreach ($address as $key => $value) {
        if (!empty($value)) {
            $address_id = insert_address($conn, $address);
            insert_uha($conn, $auth_id, $address_id);
            break;
        }
    }
    //send_mail($_POST['form_email']);
    creation_succesful($auth_id);
}

function creation_succesful($auth_id)
{
    //check if session exists 
    $_SESSION['logged_in'] = true;
    $_SESSION['auth_id'] = $auth_id;
    $_SESSION['admin'] = false;
    $_SESSION['expire'] = time() + 3600;
    //set session duration to 1 hour
    //navitage to homepage
    //todo make session last longer if user is active on website
    header('Location: ' . URL_ROOT . '/home');
    exit();
}

function insert_uha($conn, $auth_id, $address_id)
{
    $sql = 'INSERT INTO user_has_address (auth_id, address_id, address_type) VALUES (?,?, 1)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$auth_id, $address_id]);
}
function insert_address($conn, $address)
{
    $sql = 'INSERT INTO address (street_name, house_number, zipcode, city, country) VALUES (?,?,?,?,?,?)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$address['street_name'], $address['house_number'], $address['zipcode'], $address['city'], $address['country'], 2]);
    return $conn->lastInsertId();
}
function insert_auth($conn, $auth)
{

    $sql = 'INSERT INTO auth (email, password, active, created_at) VALUES (?,?, 1, NOW())';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$auth['email'], $auth['password']]);

    return $conn->lastInsertId();
}
function insert_user($conn, $user)
{
    //strip + from phone number
    $sql = 'INSERT INTO user (auth_id, first_name, infix, last_name, phone_number) VALUES (?,?,?,?,?)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$user['auth_id'], $user['first_name'], $user['infix'], $user['last_name'],  $user['phone_number']]);
}
