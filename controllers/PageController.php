<?php

include_once '../helpers/database.php';
// if the function doenst return a value
function homepage(): void
{
    $values = [];
    //get db connection
    $conn = getDbConnection();

    $sql = "SELECT id,product_name,img_path FROM product";
    //check if data is enter into the searchfield.
    if (isset($_GET["search"])) {
        // if there is a value in search field  add the WHERE clause to the query
        $sql .= " WHERE `product_name` LIKE :search";
        // check if the value contains  the criteria that was enter into the searchfield
        $values = ['search' => '%' . $_GET['search'] . '%'];
    }
    //prepare SQL  Query
    $r = $conn->prepare($sql);
    // Execute Query
    $r->execute($values);
     // Get result from Query
    $products = $r->fetchAll();
     //Close connection
    $conn = null;
//load productlist.phtml
    require_once('../views/productlist.phtml');
}
