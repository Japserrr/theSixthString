<?php

// get the database connection
$conn = getDbConnection();

function profile($errors = null, $values = null)
{


    require_once '../views/profile/profile.phtml';
}
function getvalues()
{
    return [
        'user' => get_user(),
        'address' => get_address(),
        'auth' => get_auth()
    ];
}


//check which inputs are changed and call the according update function
//todo make this working.
function update_profile()
{
    $values = getvalues();
    $user = $values['user'];
    $address = $values['address'];
    $auth = $values['auth'];
    //check if any of the user inputs are changed
    if ($user['first_name'] != $_POST['first_name'] || $user['infix'] != $_POST['infix'] || $user['last_name'] != $_POST['last_name'] || $user['phone_number'] != $_POST['phone_number']) {
        update_user();
    }
    //check if any of the address inputs are changed
    if ($address['street'] != $_POST['street'] || $address['city'] != $_POST['city'] || $address['state'] != $_POST['state'] || $address['zip'] != $_POST['zip']) {
        update_address();
    }
    //check if any of the auth inputs are changed
    if ($auth['email'] != $_POST['email'] || $auth['password'] != $_POST['password']) {
        update_auth();
    }
    header('Location: /profile');
    exit;
}
function update_address()
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE address SET street = :street, city = :city, state = :state, zip = :zip WHERE id = (SELECT address_id FROM user_has_address WHERE auth_id = :auth_id)", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'street' => $_POST['street'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip' => $_POST['zip'],
            'auth_id' => 1 //$_SESSION['auth_id']
        ]
    );
}
function check_email_availability()
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM auth WHERE email = :email", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'email' => $_POST['email']
        ]
    );
    $email = $stmt->fetch(PDO::FETCH_ASSOC);
    return $email;
}
//update the auth table, first check if the email is available and hash the password the same way it was done when the user registered
function update_auth()
{
    $conn = getDbConnection();
    //check if email is available
    $email = check_email_availability();
    if ($email) {
        if ($email['id'] != 1) {
            $_SESSION['errors'] = ['email' => 'Email is already in use'];
            header('Location: /profile');
            exit;
        }
    }
    $stmt = $conn->prepare("UPDATE auth SET email = :email, password = :password WHERE id = :auth_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'email' => $_POST['email'],
            'password' => password_hash($_POST['form_password'], PASSWORD_BCRYPT),
            'auth_id' => 1 //$_SESSION['auth_id']
        ]
    );
}
function update_user()
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE user SET first_name = :first_name,infix = :infix last_name = :last_name, phone = :phone WHERE auth_id = :auth_id", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(
        [
            'first_name' => $_POST['first_name'],
            'infix' => $_POST['infix'] ?? '',
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone'],
            'auth_id' => 1 //$_SESSION['auth_id']
        ]
    );
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
