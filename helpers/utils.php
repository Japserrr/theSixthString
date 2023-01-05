<?php

//return auth_id from session
function getAuthId(): bool|int
{
    if (isset($_SESSION['auth_id'])) {
        return $_SESSION['auth_id'];
    }
    return false;
}


//return session
function getSession(): array|bool
{
    if (isset($_SESSION)) {
        return $_SESSION;
    }
    return false;
}
//extend session time if user is active
function extendSession()
{
    if (isset($_SESSION['expire'])) {
        //check if session['expire'] is more than current time, if so extend session time with 1 hour 
        if ($_SESSION['expire'] > time()) {
            $_SESSION['expire'] = time() + 3600;
        }
    }
}
function required_login_pages(): bool
{

    //have a list of all the pages that require login
    switch ($_SERVER['REQUEST_URI']) {
        case URL_ROOT . '/logout':
        case URL_ROOT . '/statistieken':
        case URL_ROOT . '/product-management':
        case URL_ROOT . '/checkout':
        case URL_ROOT . '/confirm-payment':
        case URL_ROOT . '/medewerkers/zoeken':
        case URL_ROOT . '/medewerkers':
            return true;

        case URL_ROOT . '/':
        case URL_ROOT . '/home':
        case URL_ROOT . '/login':
        case URL_ROOT . '/register':
        case URL_ROOT . '/product':
        default:
            return false;
    }
}
function create_session($auth, $admin)
{
    //create session 

    $_SESSION['logged_in'] = true;
    $_SESSION['auth_id'] = $auth;
    $_SESSION['admin'] =  $admin;
    $_SESSION['expire'] = time() + 3600;
}

//check if user is logged in with session
function isLoggedIn(): bool
{

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }

    return false;
}
//check if session is expired
function check_expire_time(): bool
{
    if (isset($_SESSION['expire'])) {
        if ($_SESSION['expire'] < time()) {
            return true;
        }
        // session is still valid
        return false;
    }
}

function removeSession()
{
    // remove all session variables
    session_unset();
    //reset session
    session_destroy();
    session_start();
    session_regenerate_id();
}


/**
 * @return bool
 */
function isAdmin(): bool
{
    return !empty($_SESSION['admin']);
}

/** @return int|null */
function userId(): ?int
{
    return empty($_SESSION['auth_id']) ? null : (int)$_SESSION['auth_id'];
}
