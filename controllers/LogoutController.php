<?php
// Logout controller
function logout()
{
    //If session is active destroy session
    if (session_status() == PHP_SESSION_ACTIVE) {
        unset($_SESSION);
        session_destroy();
    }
    //redirect to login
    echo '<script>window.location.href = "' . URL_ROOT . '/login";</script>';

    exit();
}
