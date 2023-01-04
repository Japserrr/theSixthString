<?php

function login($error = null)
{

    //check if session is active and empty if so reset session
    // if (session_status() == 2 && empty($_SESSION)) {
    //     session_reset();
    // }



    require_once '../views/login/login.phtml';
}


function login_account()
{
    //get email and password from $_POST
    $email =  htmlentities($_POST['form_email'], ENT_QUOTES, "UTF-8");
    $password = htmlentities($_POST['form_password'], ENT_QUOTES, "UTF-8");

    //check if record exists in database
    $auth = get_account_status($email, $password);
    if (!$auth) {
        return [
            "email" => "Email of wachtwoord is onjuist"
        ];
    }

    //create session 
    create_session($auth['id'], get_employee($auth['id']));

    header('Location: ' . URL_ROOT . '/home');
    exit();
}
function get_employee($auth_id): bool
{
    $conn = getDbConnection();

    //prepare statement
    $sth = $conn->prepare('SELECT * FROM user WHERE auth_id = ?', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$auth_id]);
    //check if record exists
    if ($sth->rowCount() > 0) {
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        return $user['employee'];
    } else {
        return false;
    }
}
function check_hash($password, $hash): bool
{

    return password_verify($password, $hash);
}
//check if account exists and if password is correct 
function get_account_status($email, $password): bool|array
{

    //get database connection
    $conn = getDbConnection();
    //build query
    $sql = 'SELECT * FROM auth WHERE email = ?';
    //prepare statement
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    //execute statement
    $sth->execute([$email]);
    //check if record exists
    if ($sth->rowCount() > 0) {
        $auth = $sth->fetch(PDO::FETCH_ASSOC);
        //check if password is correct
        if (!check_hash($password, $auth['password'])) {
            //password is incorrect
            return false;
        }
        if ($auth['active'] == 1) {
            //account is active
            return $auth;
        } else {
            //account is not active
            return false;
        }
    } else {
        //account does not exist
        return false;
    }
}
