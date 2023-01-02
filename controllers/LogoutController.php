<?php

function logout()
{

    if (session_status() == PHP_SESSION_ACTIVE) {
        unset($_SESSION);
        session_destroy();
    }

    header('Location: ' . URL_ROOT . '/login');
    exit();
}
