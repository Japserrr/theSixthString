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
        if ($_SESSION['expire'] < time()) {
            $_SESSION['expire'] = time() + 3600;
        }
    }
}
