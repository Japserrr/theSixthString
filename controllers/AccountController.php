<?php

// get the database connection
$conn = getDbConnection();
session_start();

function profile()
{

    var_dump(get_address());
    require_once '../views/profile/profile.phtml';
}

//function that gets the address id from user_has_address with the auth_id in session and returns the address from the address table
function get_address()
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT address_id FROM user_has_address WHERE auth_id = :auth_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'auth_id' =>  1 //$_SESSION['auth_id']
        ]
    );
    $address_id = $stmt->fetch(PDO::FETCH_ASSOC);


    $stmt = $conn->prepare("SELECT * FROM address WHERE id = :address_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'address_id' => $address_id['address_id']
        ]
    );
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    return $address;
}
