<?php

function isLoggedIn()
{
    //if session is null or logged_in is false redirect to login page
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
        header('Location: ' . URL_ROOT . '/login');
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