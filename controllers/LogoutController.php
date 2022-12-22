<?php

function logout()
{
    if (session_status() == PHP_SESSION_ACTIVE) {

        session_destroy();
        login();
        exit();
    }
}
