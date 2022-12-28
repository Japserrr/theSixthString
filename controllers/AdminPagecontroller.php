<?php

include_once '../helpers/database.php';

function AdminPage()
{

    require_once('../views/admin.phtml');
}

function SelectEmployee()
{
    $conn = getDbConnection();
   
    $r = $conn->prepare("SELECT auth_id FROM employee WHERE auth_id = ?");
    $r->execute();
    $product = $r->fetchAll();
    $conn = null;
    return $product;

}

function UpdateEmployee()
{
    
    $conn = getDbConnection();
    $r = $conn->prepare("UPDATE employee SET auth_id WHERE ? ", [PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY]);

    $r->execute([/**hier komt je auth_id variable */]);
return $r;
}