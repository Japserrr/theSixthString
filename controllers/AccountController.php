<?php

// get the database connection
$conn = getDbConnection();

function profile($errors = null, $values = null)
{


    //get data from all 3 functions and push it into $values array 
    $values = [
        'user' => get_user(),
        'address' => get_address(),
        'auth' => get_auth()
    ];

    require_once '../views/profile/profile.phtml';
}
function getvalues()
{
    return [
        'user' => get_user(),
        'address' => get_address(),
        'auth' => get_auth()
    ];;
}
//function that gets the address id from user_has_address but only if the record exists in user_has_address with the auth_id in session and returns the address from the address table
function get_address()
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT * FROM address WHERE id = (SELECT address_id FROM user_has_address WHERE auth_id = :auth_id)", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'auth_id' =>  1 // $_SESSION['auth_id']
        ]
    );
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    return $address;
}




//function that gets the user data from the user table with the auth_id in session
function get_user()
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT * FROM user WHERE auth_id = :auth_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'auth_id' =>  1 //$_SESSION['auth_id']
        ]
    );
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}
//function that gets auth data from the auth table with the auth_id in session
function get_auth()
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT email FROM auth WHERE id = :auth_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'auth_id' => 1 //  $_SESSION['auth_id']
        ]
    );
    $auth = $stmt->fetch(PDO::FETCH_ASSOC);
    return $auth;
}
