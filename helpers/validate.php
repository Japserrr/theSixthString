<?php

function isLoggedIn()
{
    //if session is null or logged_in is false redirect to login page
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
        return false;
    }
    return true;
}
