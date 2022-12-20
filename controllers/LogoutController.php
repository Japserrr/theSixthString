<?php

function logout()
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
        header('Location: ' . URL_ROOT . '/login');
    }
}
