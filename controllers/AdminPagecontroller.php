<?php

include_once '../helpers/database.php';
//load admin.phtml  so that employees can be used.
function adminPage()
{   
   
    $employees = getAllEmployee();
    require_once('../views/admin.phtml');
}
//get all employees
function getAllEmployee()
{
    //get db connection
    $conn = getDbConnection();
    //prepare SQL  Query
    $r = $conn->prepare("
    SELECT u.*, 
    a.email
    FROM user AS u
    INNER JOIN auth AS a ON a.id = u.auth_id  
    WHERE u.employee = 1");
    // Excute Query
    $r->execute();
    // Get result from Query
    $users = $r->fetchAll();
    //Close connection
    $conn = null;
    //Return Users
    return $users;
}
//select employee
function selectEmployee()
{
    //fetches email from POST with array key searchfield
    $email = $_POST['searchField'];
//get db connection
    $conn = getDbConnection();
    //prepare SQL  Query
    $r = $conn->prepare("
        SELECT 
        u.*,
        a.email
        FROM user AS u 
        INNER JOIN auth AS a ON a.id = u.auth_id 
        WHERE a.email LIKE ?
    ");
    // Execute Query
    $r->execute(["%$email%"]);
    // Get result from Query
    $employees = $r->fetchAll();
     //Close connection
    $conn = null;
    //load admin.phtml
    require_once('../views/admin.phtml');
}

function updateEmployee()
{
    //fetches ID from POST with array key ID
    $id = $_POST['id'];
    //  Converts the value of employee to an integer.
    $employee = intval($_POST['employee']);
    //get db connection
    $conn = getDbConnection();
//prepare and executes SQL  Query
    $r = $conn->prepare("UPDATE user SET employee = :employee WHERE auth_id = :id")->execute(['employee' => $employee, 'id' => $id]);
//Close connection
    $conn = null;
    // redirect to /medewerkers
    header('Location: ' . URL_MAIN . URL_ROOT . '/medewerkers');
    exit;
}
