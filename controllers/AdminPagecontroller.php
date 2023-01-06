<?php

include_once '../helpers/database.php';

function adminPage()
{
    $employees = getAllEmployee();
    require_once('../views/admin.phtml');
}

function getAllEmployee()
{
    $conn = getDbConnection();
    $r = $conn->prepare("
    SELECT u.*, 
    a.email
    FROM user AS u
    INNER JOIN auth AS a ON a.id = u.auth_id  
    WHERE u.employee = 1");
    $r->execute();
    $users = $r->fetchAll();
    $conn = null;
    return $users;
}

function selectEmployee()
{
    $email = $_POST['searchField'];

    $conn = getDbConnection();
    $r = $conn->prepare("
        SELECT 
        u.*,
        a.email
        FROM user AS u 
        INNER JOIN auth AS a ON a.id = u.auth_id 
        WHERE a.email LIKE ?
    ");
    $r->execute(["%$email%"]);
    $employees = $r->fetchAll();
    $conn = null;

    require_once('../views/admin.phtml');
}

function updateEmployee()
{
    // validation toevoegenb
    $id = $_POST['id'];
    $employee = intval($_POST['employee']);
    $conn = getDbConnection();

    $r = $conn->prepare("UPDATE user SET employee = :employee WHERE auth_id = :id")->execute(['employee' => $employee, 'id' => $id]);

    $conn = null;
    echo '<script>window.location.href = "' . URL_ROOT . '/medewerkers";</script>';
    exit;
}
