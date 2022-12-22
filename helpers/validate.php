<?php

function isLoggedIn()
{
    //if session is null or logged_in is false or the session has expired redirect to login
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false || $_SESSION['expire'] < time()) {

        //clear session
        $_SESSION = [];
        //destroy session
        session_destroy();
        login();
        exit();
    }
    return true;
}
