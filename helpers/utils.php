<?php

//return auth_id from session
function getAuthId()
{
    if (isset($_SESSION['auth_id'])) {
        return $_SESSION['auth_id'];
    }
    return false;
}
