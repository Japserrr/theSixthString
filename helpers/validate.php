<?php
function isLoggedIn()
{
    if (session_status() == 1 || empty($_SESSION)) {
        return false;
    }

    //if session is null or logged_in is false or the session has expired redirect to login
    if (($_SESSION['logged_in'] == false || $_SESSION['expire'] < time()) && session_status() == 2) {

        //clear session
        $_SESSION = [];
        // remove all session variables
        session_unset();
        //destroy session
        // session_destroy();
        header('Location: ' . URL_ROOT . '/login');
        exit();
    }
    return true;
}


 

/**
 * @return bool
 */
function isAdmin(): bool
{
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
        return false;
    }
    return true;
}

/** @return int|null */
function userId(): ?int
{
    return empty($_SESSION['auth_id']) ? null : (int)$_SESSION['auth_id'];
}
function check_expire_time()
{
    if (session_status() == 2 && !empty($_SESSION)) {
        if ($_SESSION['expire'] < time()) {
            //clear session
            $_SESSION = [];
            // remove all session variables
            session_unset();
            //destroy session

            session_reset();
            header('Location: ' . URL_ROOT . '/login');
            exit();
        }
    }
}
