<?php

//return auth_id from session
function getAuthId()
{
    if (isset($_SESSION['auth_id'])) {
        return $_SESSION['auth_id'];
    }
    return false;
}


//return session
function getSession()
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
function create_session($auth, $user = null)
{
    //create session 
    $_SESSION['logged_in'] = true;
    $_SESSION['auth_id'] = $auth;
    $_SESSION['admin'] = empty($user) ? false : $user['admin'];


    $_SESSION['expire'] = time() + 3600;
}

//check if user is logged in with session
function isLoggedIn()
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
    return false;
}
//check if session is expired
function check_expire_time()
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
    if (!isset($_SESSION['auth_id'])) {
        return false;
    }

    require_once '../helpers/database.php';

    $conn = getDbConnection();
    $stmt = $conn->prepare('SELECT employee FROM user WHERE auth_id = :auth_id');
    $stmt->execute(['auth_id' => $_SESSION['auth_id']]);
    $isEmployee = $stmt->fetch();
    $conn = null;

    if (!$isEmployee['employee']) {
        return false;
    }

    return true;
}

/** @return int|null */
function userId(): ?int
{
    return empty($_SESSION['auth_id']) ? null : (int)$_SESSION['auth_id'];
}
