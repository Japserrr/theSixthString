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
        return login();
    }
    return true;
}
