<?php

function login($error = null)
{
    require_once '../views/login/login.phtml';
}
function login_account()
{
    //get email and password from $_POST
    $email = $_POST['form_email'];
    $password = $_POST['form_password'];
    //check if record exists in database
    $auth = get_account_status($email, $password);
    if (!$auth) {
        return [
            "email" => "Email of wachtwoord is onjuist"
        ];
    }
    //create session 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['logged_in'] = true;
    $_SESSION['auth_id'] = $auth['id'];
    $_SESSION['admin'] = false;
    $_POST = [];
    header('Location: ' . URL_ROOT . '/home');
}

function get_account_status($email, $password)
{
    $conn = getDbConnection();
    $sql = 'SELECT * FROM auth WHERE email = ? AND password = SHA2(?, 256)';
    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([$email, $password]);
    //check if record exists
    if ($sth->rowCount() > 0) {
        $auth = $sth->fetch(PDO::FETCH_ASSOC);
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
